<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class OutPass extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pass_code',
        'pass_type',
        'status',
        'requester_type',
        'requester_id',
        'out_date',
        'out_time',
        'expected_return_time',
        'actual_return_time',
        'purpose',
        'destination',
        'remarks',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'visitor_name',
        'visitor_contact',
        'visitor_address',
        'visitor_id_proof',
        'visitor_company',
        'person_to_meet',
        'vehicle_number',
        'vehicle_type',
        'vehicle_model',
        'driver_name',
        'driver_contact',
        'driver_address',
        'driver_license',
        'accompany_data',
        'passenger_data',
        'additional_data',
        'qr_code',
        'bar_code',
        'generated_ip',
        'approved_ip',
        'institute_id',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'out_date' => 'date',
        'out_time' => 'datetime:H:i',
        'expected_return_time' => 'datetime:H:i',
        'actual_return_time' => 'datetime',
        'approved_at' => 'datetime',
        'accompany_data' => 'array',
        'passenger_data' => 'array',
        'additional_data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_out_datetime',
        'is_expired',
        'status_color',
    ];

    /**
     * Get the requester model (polymorphic).
     */
    public function requester(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who approved the out pass.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who created the out pass.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the institute that owns the out pass.
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(InstituteAdmin::class, 'institute_id');
    }

    /**
     * Get the accompany persons for the out pass.
     */
    public function accompanyPersons(): HasMany
    {
        return $this->hasMany(AccompanyPerson::class);
    }

    /**
     * Get the passengers for the out pass.
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Get the history for the out pass.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(OutPassHistory::class);
    }

    /**
     * Get the PDF generations for the out pass.
     */
    public function pdfGenerations(): HasMany
    {
        return $this->hasMany(PdfGeneration::class);
    }

    /**
     * Get the approvals for the out pass.
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(OutPassApproval::class);
    }

    /**
     * Format out datetime.
     */
    public function getFormattedOutDatetimeAttribute(): string
    {
        if ($this->out_date && $this->out_time) {
            return Carbon::parse($this->out_date->format('Y-m-d') . ' ' . $this->out_time)->format('M d, Y h:i A');
        }
        return 'Not set';
    }

    /**
     * Check if pass is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        if ($this->status === 'used' || $this->status === 'expired') {
            return true;
        }

        if ($this->out_date && $this->out_time) {
            $outDateTime = Carbon::parse($this->out_date->format('Y-m-d') . ' ' . $this->out_time);
            $expiryTime = $outDateTime->copy()->addHours(24); // Default 24 hours validity
            
            return now()->greaterThan($expiryTime) && $this->status !== 'approved';
        }

        return false;
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            'expired' => 'dark',
            'used' => 'info',
            default => 'primary'
        };
    }

    /**
     * Generate unique pass code.
     */
    public static function generatePassCode(string $type): string
    {
        $prefixes = [
            'student' => 'STU',
            'employee' => 'EMP',
            'vehicle' => 'VEH',
            'visitor' => 'VIS'
        ];

        $prefix = $prefixes[$type] ?? 'OUT';
        $date = now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -3));
        
        // Get count of today's passes
        $count = self::whereDate('created_at', today())->count() + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$sequence}-{$random}";
    }

    /**
     * Scope a query to only include pending passes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved passes.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include today's passes.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('out_date', today());
    }

    /**
     * Scope a query to filter by pass type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('pass_type', $type);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('out_date', [$startDate, $endDate]);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($outPass) {
            if (empty($outPass->pass_code)) {
                $outPass->pass_code = self::generatePassCode($outPass->pass_type);
            }
        });

        static::created(function ($outPass) {
            // Create initial history entry
            $outPass->histories()->create([
                'user_id' => $outPass->created_by,
                'action' => 'created',
                'new_status' => $outPass->status,
                'ip_address' => $outPass->generated_ip,
            ]);
        });
    }
}