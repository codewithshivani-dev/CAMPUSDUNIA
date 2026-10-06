<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddRoomName extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_names';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'institute_id',
        'branch_id',
        'room_id',
        'official_name',
        'alternative_name',
        'purpose',
        'department',
        'in_charge_name',
        'in_charge_contact',
        'special_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the institute that owns the room name.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the room name.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the room associated with this name.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Scope a query to only include room names for a specific department.
     */
    public function scopeDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to only include room names with specific purpose.
     */
    public function scopePurpose($query, $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    /**
     * Scope a query to search room names.
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('official_name', 'like', "%{$searchTerm}%")
                  ->orWhere('alternative_name', 'like', "%{$searchTerm}%")
                  ->orWhere('purpose', 'like', "%{$searchTerm}%")
                  ->orWhere('department', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Get all available names for the room.
     */
    public function getAllNamesAttribute()
    {
        $names = [$this->official_name];
        
        if ($this->alternative_name) {
            $names[] = $this->alternative_name;
        }
        
        return $names;
    }

    /**
     * Get display name (prefers alternative name if exists).
     */
    public function getDisplayNameAttribute()
    {
        return $this->alternative_name ?: $this->official_name;
    }

    /**
     * Get full name with department prefix.
     */
    public function getFullNameAttribute()
    {
        $name = $this->display_name;
        
        if ($this->department) {
            $name = "{$this->department} - {$name}";
        }
        
        return $name;
    }

    /**
     * Get formatted in-charge information.
     */
    public function getInChargeInfoAttribute()
    {
        $info = [];
        
        if ($this->in_charge_name) {
            $info[] = $this->in_charge_name;
        }
        
        if ($this->in_charge_contact) {
            $info[] = $this->in_charge_contact;
        }
        
        return implode(' | ', $info);
    }

    /**
     * Check if room has alternative name.
     */
    public function hasAlternativeName()
    {
        return !empty($this->alternative_name);
    }

    /**
     * Check if room has in-charge assigned.
     */
    public function hasInCharge()
    {
        return !empty($this->in_charge_name);
    }

    /**
     * Check if room has department assignment.
     */
    public function hasDepartment()
    {
        return !empty($this->department);
    }

    /**
     * Check if room has purpose defined.
     */
    public function hasPurpose()
    {
        return !empty($this->purpose);
    }

    /**
     * Get room name with location context.
     * Requires room relationship to be loaded
     */
    public function getContextualNameAttribute()
    {
        $context = [];
        
        if ($this->room && $this->room->floor) {
            if ($this->room->floor->building) {
                $context[] = $this->room->floor->building->name;
            }
            
            if ($this->room->floor->block) {
                $context[] = $this->room->floor->block->name;
            }
            
            $context[] = $this->room->floor->display_name;
        }
        
        $context[] = $this->display_name;
        
        return implode(' - ', $context);
    }

    /**
     * Update or create room name for a room.
     */
    public static function updateOrCreateForRoom($roomId, $data)
    {
        return self::updateOrCreate(
            ['room_id' => $roomId],
            $data
        );
    }

    /**
     * Get names grouped by department.
     */
    public static function getByDepartment()
    {
        return self::select('department', 'official_name', 'alternative_name', 'room_id')
                   ->whereNotNull('department')
                   ->orderBy('department')
                   ->orderBy('official_name')
                   ->get()
                   ->groupBy('department');
    }

    /**
     * Get names grouped by purpose.
     */
    public static function getByPurpose()
    {
        return self::select('purpose', 'official_name', 'alternative_name', 'room_id')
                   ->whereNotNull('purpose')
                   ->orderBy('purpose')
                   ->orderBy('official_name')
                   ->get()
                   ->groupBy('purpose');
    }

    /**
     * Search rooms by in-charge name.
     */
    public static function searchByInCharge($name)
    {
        return self::where('in_charge_name', 'like', "%{$name}%")
                   ->orderBy('in_charge_name')
                   ->get();
    }

    /**
     * Bulk update department for multiple rooms.
     */
    public static function bulkUpdateDepartment($roomIds, $department)
    {
        return self::whereIn('room_id', $roomIds)
                   ->update(['department' => $department]);
    }

    /**
     * Get statistics about room naming.
     */
    public static function getNamingStatistics()
    {
        $total = self::count();
        $withAlternative = self::whereNotNull('alternative_name')->count();
        $withDepartment = self::whereNotNull('department')->count();
        $withPurpose = self::whereNotNull('purpose')->count();
        $withInCharge = self::whereNotNull('in_charge_name')->count();
        
        return [
            'total_rooms_named' => $total,
            'with_alternative_name' => $withAlternative,
            'with_department' => $withDepartment,
            'with_purpose' => $withPurpose,
            'with_in_charge' => $withInCharge,
            'alternative_name_percentage' => $total > 0 ? round(($withAlternative / $total) * 100, 2) : 0,
            'department_assignment_percentage' => $total > 0 ? round(($withDepartment / $total) * 100, 2) : 0,
            'purpose_assignment_percentage' => $total > 0 ? round(($withPurpose / $total) * 100, 2) : 0,
            'in_charge_assignment_percentage' => $total > 0 ? round(($withInCharge / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get department-wise room count.
     */
    public static function getDepartmentWiseCount()
    {
        return self::select('department')
                   ->selectRaw('COUNT(*) as room_count')
                   ->whereNotNull('department')
                   ->groupBy('department')
                   ->orderBy('department')
                   ->get()
                   ->pluck('room_count', 'department');
    }

    /**
     * Get purpose-wise room count.
     */
    public static function getPurposeWiseCount()
    {
        return self::select('purpose')
                   ->selectRaw('COUNT(*) as room_count')
                   ->whereNotNull('purpose')
                   ->groupBy('purpose')
                   ->orderBy('purpose')
                   ->get()
                   ->pluck('room_count', 'purpose');
    }

    /**
     * Get rooms without department assignment.
     */
    public static function getRoomsWithoutDepartment()
    {
        return self::whereNull('department')
                   ->orderBy('official_name')
                   ->get();
    }

    /**
     * Get rooms without purpose.
     */
    public static function getRoomsWithoutPurpose()
    {
        return self::whereNull('purpose')
                   ->orderBy('official_name')
                   ->get();
    }

    /**
     * Get rooms without in-charge.
     */
    public static function getRoomsWithoutInCharge()
    {
        return self::whereNull('in_charge_name')
                   ->orderBy('official_name')
                   ->get();
    }

    /**
     * Get room name suggestions based on purpose and department.
     */
    public function getNamingSuggestions()
    {
        $suggestions = [];
        
        if ($this->purpose && $this->department) {
            $suggestions[] = "{$this->department} {$this->purpose} Room";
        }
        
        if ($this->purpose && $this->room && $this->room->room_number) {
            $suggestions[] = "{$this->purpose} Room {$this->room->room_number}";
        }
        
        if ($this->in_charge_name && $this->purpose) {
            $firstName = explode(' ', $this->in_charge_name)[0];
            $suggestions[] = "{$firstName}'s {$this->purpose} Room";
        }
        
        return array_unique($suggestions);
    }
}