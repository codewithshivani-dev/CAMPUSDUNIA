<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounsellingTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_process_config_id',
        'day_of_week',
        'slot_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'capacity',
        'booked_count',
        'status',
        'is_break_slot',
        'notes',
        'sort_order'
    ];

    protected $casts = [
        'slot_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'capacity' => 'integer',
        'booked_count' => 'integer',
        'is_break_slot' => 'boolean',
        'sort_order' => 'integer'
    ];

    protected $appends = [
        'available_count',
        'time_display',
        'is_available',
        'occupancy_percentage'
    ];

    /**
     * Get the admission process config that owns the time slot.
     */
    public function admissionProcessConfig(): BelongsTo
    {
        return $this->belongsTo(AdmissionProcessConfig::class);
    }

    /**
     * Get the bookings for the time slot.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(CounsellingBooking::class, 'counselling_time_slot_id');
    }

    /**
     * Get the available count attribute.
     */
    public function getAvailableCountAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_count);
    }

    /**
     * Get the time display attribute.
     */
    public function getTimeDisplayAttribute(): string
    {
        return date('h:i A', strtotime($this->start_time)) . ' - ' . 
               date('h:i A', strtotime($this->end_time));
    }

    /**
     * Get the is available attribute.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'active' && $this->available_count > 0;
    }

    /**
     * Get the occupancy percentage attribute.
     */
    public function getOccupancyPercentageAttribute(): float
    {
        if ($this->capacity === 0) {
            return 0;
        }
        
        return round(($this->booked_count / $this->capacity) * 100, 2);
    }

    /**
     * Scope a query to only include active slots.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include slots for a specific day.
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Scope a query to only include slots for specific dates.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('slot_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include available slots.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->whereRaw('capacity > booked_count');
    }

    /**
     * Scope a query to only include slots for a specific config.
     */
    public function scopeForConfig($query, $configId)
    {
        return $query->where('admission_process_config_id', $configId);
    }

    /**
     * Check if the slot is full.
     */
    public function isFull(): bool
    {
        return $this->booked_count >= $this->capacity;
    }

    /**
     * Book a slot.
     */
    public function bookSlot($studentId, $applicationId = null): bool
    {
        if ($this->isFull() || $this->status !== 'active') {
            return false;
        }

        $this->increment('booked_count');
        return true;
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking(): bool
    {
        if ($this->booked_count > 0) {
            $this->decrement('booked_count');
            return true;
        }
        
        return false;
    }

    /**
     * Generate slots for multiple dates.
     */
    public static function generateSlotsForDates($configId, array $dates, array $slotData): array
    {
        $createdSlots = [];
        
        foreach ($dates as $date) {
            $dayOfWeek = date('l', strtotime($date));
            
            $slot = self::create([
                'admission_process_config_id' => $configId,
                'day_of_week' => $dayOfWeek,
                'slot_date' => $date,
                'start_time' => $slotData['start_time'],
                'end_time' => $slotData['end_time'],
                'duration_minutes' => $slotData['duration_minutes'],
                'capacity' => $slotData['capacity'],
                'status' => 'active'
            ]);
            
            $createdSlots[] = $slot;
        }
        
        return $createdSlots;
    }

    /**
     * Generate slots based on working hours and breaks.
     */
    public static function generateSlotsFromSchedule($configId, $config): array
    {
        $slots = [];
        $startTime = strtotime($config->counselling_working_start);
        $endTime = strtotime($config->counselling_working_end);
        $breakStart = strtotime($config->counselling_break_start);
        $breakEnd = strtotime($config->counselling_break_end);
        $duration = $config->counselling_session_duration * 60; // Convert to seconds
        
        // Get selected days and dates
        $selectedDays = $config->counselling_days ?? [];
        $startDate = new \DateTime($config->counselling_start_date);
        $endDate = new \DateTime($config->counselling_end_date);
        
        // Loop through each date in range
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($startDate, $interval, $endDate);
        
        foreach ($period as $date) {
            $dayOfWeek = $date->format('l');
            
            // Check if this day is selected
            if (!in_array($dayOfWeek, $selectedDays)) {
                continue;
            }
            
            // Generate slots for this day
            $currentTime = $startTime;
            
            while ($currentTime + $duration <= $endTime) {
                // Check if slot falls in break time
                $slotEndTime = $currentTime + $duration;
                $isBreakSlot = ($currentTime >= $breakStart && $currentTime < $breakEnd) ||
                              ($slotEndTime > $breakStart && $slotEndTime <= $breakEnd);
                
                if (!$isBreakSlot) {
                    $slots[] = [
                        'admission_process_config_id' => $configId,
                        'day_of_week' => $dayOfWeek,
                        'slot_date' => $date->format('Y-m-d'),
                        'start_time' => date('H:i:s', $currentTime),
                        'end_time' => date('H:i:s', $slotEndTime),
                        'duration_minutes' => $config->counselling_session_duration,
                        'capacity' => $config->counselling_max_candidates,
                        'status' => 'active',
                        'is_break_slot' => false
                    ];
                }
                
                $currentTime = $slotEndTime;
            }
        }
        
        // Insert all slots at once for better performance
        if (!empty($slots)) {
            self::insert($slots);
        }
        
        return $slots;
    }
}