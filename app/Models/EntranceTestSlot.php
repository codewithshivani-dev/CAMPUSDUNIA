<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntranceTestSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_process_config_id',
        'test_id',
        'test_name',
        'test_date',
        'slot_number',
        'start_time',
        'end_time',
        'duration_minutes',
        'capacity',
        'booked_count',
        'status',
        'venue',
        'online_platform',
        'instructions'
    ];

    protected $casts = [
        'test_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'capacity' => 'integer',
        'booked_count' => 'integer'
    ];

    protected $appends = [
        'available_count',
        'time_display',
        'is_available'
    ];

    /**
     * Get the admission process config that owns the test slot.
     */
    public function admissionProcessConfig(): BelongsTo
    {
        return $this->belongsTo(AdmissionProcessConfig::class);
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
        return $this->status === 'scheduled' && $this->available_count > 0;
    }

    /**
     * Scope a query to only include scheduled slots.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include slots for a specific test.
     */
    public function scopeForTest($query, $testId)
    {
        return $query->where('test_id', $testId);
    }

    /**
     * Scope a query to only include slots for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('test_date', $date);
    }

    /**
     * Book a test slot.
     */
    public function bookSlot(): bool
    {
        if ($this->isFull() || $this->status !== 'scheduled') {
            return false;
        }

        $this->increment('booked_count');
        return true;
    }

    /**
     * Check if the slot is full.
     */
    public function isFull(): bool
    {
        return $this->booked_count >= $this->capacity;
    }

    /**
     * Generate slots from test configuration.
     */
    public static function generateFromTestConfig($configId, $test): array
    {
        $slots = [];
        $testDate = $test['date'];
        $duration = $test['duration'];
        
        if (isset($test['slotConfiguration'])) {
            $slotConfig = $test['slotConfiguration'];
            
            for ($i = 1; $i <= $slotConfig['numberOfSlots']; $i++) {
                $startTime = $slotConfig['slotTimes'][$i-1] ?? '09:00';
                $capacity = $slotConfig['slotCapacities'][$i-1] ?? 50;
                
                // Calculate end time
                [$hours, $minutes] = explode(':', $startTime);
                $endTime = date('H:i', strtotime("+{$duration} minutes", strtotime($startTime)));
                
                $slots[] = [
                    'admission_process_config_id' => $configId,
                    'test_id' => $test['id'],
                    'test_name' => $test['name'],
                    'test_date' => $testDate,
                    'slot_number' => $i,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_minutes' => $duration,
                    'capacity' => $capacity,
                    'status' => 'scheduled'
                ];
            }
        }
        
        // Insert all slots
        if (!empty($slots)) {
            self::insert($slots);
        }
        
        return $slots;
    }
}