<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddFloorAmeneties extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'add_floors_amenities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'institute_id',
        'branch_id',
        'floor_id',
        
        // Electrical
        'has_backup_generator',
        'has_ups',
        'power_sockets_count',
        'light_points_count',
        'fan_points_count',
        
        // Network & Communication
        'has_network_cabling',
        'has_intercom',
        'has_telephone_lines',
        'network_ports_count',
        
        // Security
        'has_cctv',
        'cctv_cameras_count',
        'has_security_desk',
        'has_access_control',
        
        // Furniture & Fixtures
        'has_chairs',
        'has_tables',
        'has_whiteboards',
        'has_smart_boards',
        'has_projectors',
        'whiteboard_count',
        'smart_board_count',
        'projector_count',
        
        // Special Rooms
        'has_server_room',
        'has_storage_room',
        'has_cleaner_room',
        'has_electric_room',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Boolean casts
        'has_backup_generator' => 'boolean',
        'has_ups' => 'boolean',
        'has_network_cabling' => 'boolean',
        'has_intercom' => 'boolean',
        'has_telephone_lines' => 'boolean',
        'has_cctv' => 'boolean',
        'has_security_desk' => 'boolean',
        'has_access_control' => 'boolean',
        'has_chairs' => 'boolean',
        'has_tables' => 'boolean',
        'has_whiteboards' => 'boolean',
        'has_smart_boards' => 'boolean',
        'has_projectors' => 'boolean',
        'has_server_room' => 'boolean',
        'has_storage_room' => 'boolean',
        'has_cleaner_room' => 'boolean',
        'has_electric_room' => 'boolean',
        
        // Integer casts
        'power_sockets_count' => 'integer',
        'light_points_count' => 'integer',
        'fan_points_count' => 'integer',
        'network_ports_count' => 'integer',
        'cctv_cameras_count' => 'integer',
        'whiteboard_count' => 'integer',
        'smart_board_count' => 'integer',
        'projector_count' => 'integer',
        
        // Timestamps
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'has_backup_generator' => false,
        'has_ups' => false,
        'power_sockets_count' => 0,
        'light_points_count' => 0,
        'fan_points_count' => 0,
        'has_network_cabling' => false,
        'has_intercom' => false,
        'has_telephone_lines' => false,
        'network_ports_count' => 0,
        'has_cctv' => false,
        'cctv_cameras_count' => 0,
        'has_security_desk' => false,
        'has_access_control' => false,
        'has_chairs' => false,
        'has_tables' => false,
        'has_whiteboards' => false,
        'has_smart_boards' => false,
        'has_projectors' => false,
        'whiteboard_count' => 0,
        'smart_board_count' => 0,
        'projector_count' => 0,
        'has_server_room' => false,
        'has_storage_room' => false,
        'has_cleaner_room' => false,
        'has_electric_room' => false,
    ];

    /**
     * Get the institute that owns the floor amenity.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the floor amenity.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the floor that owns the amenity.
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    /**
     * Scope a query to only include amenities with backup generator.
     */
    public function scopeWithBackupGenerator($query)
    {
        return $query->where('has_backup_generator', true);
    }

    /**
     * Scope a query to only include amenities with UPS.
     */
    public function scopeWithUps($query)
    {
        return $query->where('has_ups', true);
    }

    /**
     * Scope a query to only include amenities with network cabling.
     */
    public function scopeWithNetworkCabling($query)
    {
        return $query->where('has_network_cabling', true);
    }

    /**
     * Scope a query to only include amenities with CCTV.
     */
    public function scopeWithCctv($query)
    {
        return $query->where('has_cctv', true);
    }

    /**
     * Scope a query to only include amenities with server room.
     */
    public function scopeWithServerRoom($query)
    {
        return $query->where('has_server_room', true);
    }

    /**
     * Scope a query to only include amenities with smart boards.
     */
    public function scopeWithSmartBoards($query)
    {
        return $query->where('has_smart_boards', true);
    }

    /**
     * Check if floor has basic electrical amenities.
     */
    public function hasBasicElectrical()
    {
        return $this->light_points_count > 0 && $this->power_sockets_count > 0;
    }

    /**
     * Check if floor has network amenities.
     */
    public function hasNetworkAmenities()
    {
        return $this->has_network_cabling || $this->has_intercom || $this->has_telephone_lines;
    }

    /**
     * Check if floor has security amenities.
     */
    public function hasSecurityAmenities()
    {
        return $this->has_cctv || $this->has_security_desk || $this->has_access_control;
    }

    /**
     * Check if floor has teaching amenities.
     */
    public function hasTeachingAmenities()
    {
        return $this->has_whiteboards || $this->has_smart_boards || $this->has_projectors;
    }

    /**
     * Check if floor has basic furniture.
     */
    public function hasBasicFurniture()
    {
        return $this->has_chairs && $this->has_tables;
    }

    /**
     * Get total electrical points count.
     */
    public function getTotalElectricalPointsAttribute()
    {
        return $this->power_sockets_count + 
               $this->light_points_count + 
               $this->fan_points_count;
    }

    /**
     * Get total teaching equipment count.
     */
    public function getTotalTeachingEquipmentAttribute()
    {
        return $this->whiteboard_count + 
               $this->smart_board_count + 
               $this->projector_count;
    }

    /**
     * Get all electrical amenities as array.
     */
    public function getElectricalAmenitiesAttribute()
    {
        $amenities = [];
        
        if ($this->has_backup_generator) {
            $amenities[] = 'Backup Generator';
        }
        
        if ($this->has_ups) {
            $amenities[] = 'UPS';
        }
        
        if ($this->power_sockets_count > 0) {
            $amenities[] = "Power Sockets ({$this->power_sockets_count})";
        }
        
        if ($this->light_points_count > 0) {
            $amenities[] = "Light Points ({$this->light_points_count})";
        }
        
        if ($this->fan_points_count > 0) {
            $amenities[] = "Fan Points ({$this->fan_points_count})";
        }
        
        return $amenities;
    }

    /**
     * Get all network amenities as array.
     */
    public function getNetworkAmenitiesAttribute()
    {
        $amenities = [];
        
        if ($this->has_network_cabling) {
            $amenities[] = 'Network Cabling';
        }
        
        if ($this->has_intercom) {
            $amenities[] = 'Intercom';
        }
        
        if ($this->has_telephone_lines) {
            $amenities[] = 'Telephone Lines';
        }
        
        if ($this->network_ports_count > 0) {
            $amenities[] = "Network Ports ({$this->network_ports_count})";
        }
        
        return $amenities;
    }

    /**
     * Get all security amenities as array.
     */
    public function getSecurityAmenitiesAttribute()
    {
        $amenities = [];
        
        if ($this->has_cctv) {
            $amenities[] = "CCTV ({$this->cctv_cameras_count} cameras)";
        }
        
        if ($this->has_security_desk) {
            $amenities[] = 'Security Desk';
        }
        
        if ($this->has_access_control) {
            $amenities[] = 'Access Control';
        }
        
        return $amenities;
    }

    /**
     * Get all furniture amenities as array.
     */
    public function getFurnitureAmenitiesAttribute()
    {
        $amenities = [];
        
        if ($this->has_chairs) {
            $amenities[] = 'Chairs';
        }
        
        if ($this->has_tables) {
            $amenities[] = 'Tables';
        }
        
        if ($this->has_whiteboards) {
            $amenities[] = "Whiteboards ({$this->whiteboard_count})";
        }
        
        if ($this->has_smart_boards) {
            $amenities[] = "Smart Boards ({$this->smart_board_count})";
        }
        
        if ($this->has_projectors) {
            $amenities[] = "Projectors ({$this->projector_count})";
        }
        
        return $amenities;
    }

    /**
     * Get all special rooms as array.
     */
    public function getSpecialRoomsAttribute()
    {
        $rooms = [];
        
        if ($this->has_server_room) {
            $rooms[] = 'Server Room';
        }
        
        if ($this->has_storage_room) {
            $rooms[] = 'Storage Room';
        }
        
        if ($this->has_cleaner_room) {
            $rooms[] = 'Cleaner Room';
        }
        
        if ($this->has_electric_room) {
            $rooms[] = 'Electric Room';
        }
        
        return $rooms;
    }

    /**
     * Get all amenities grouped by category.
     */
    public function getAllAmenitiesByCategoryAttribute()
    {
        return [
            'electrical' => $this->electrical_amenities,
            'network' => $this->network_amenities,
            'security' => $this->security_amenities,
            'furniture' => $this->furniture_amenities,
            'special_rooms' => $this->special_rooms,
        ];
    }

    /**
     * Get summary of amenities.
     */
    public function getAmenitiesSummaryAttribute()
    {
        $summary = [];
        
        $electricalCount = count($this->electrical_amenities);
        $networkCount = count($this->network_amenities);
        $securityCount = count($this->security_amenities);
        $furnitureCount = count($this->furniture_amenities);
        $specialRoomsCount = count($this->special_rooms);
        
        if ($electricalCount > 0) {
            $summary[] = "{$electricalCount} Electrical Amenities";
        }
        
        if ($networkCount > 0) {
            $summary[] = "{$networkCount} Network Amenities";
        }
        
        if ($securityCount > 0) {
            $summary[] = "{$securityCount} Security Amenities";
        }
        
        if ($furnitureCount > 0) {
            $summary[] = "{$furnitureCount} Furniture Items";
        }
        
        if ($specialRoomsCount > 0) {
            $summary[] = "{$specialRoomsCount} Special Rooms";
        }
        
        return $summary;
    }

    /**
     * Check if floor is well-equipped for teaching.
     */
    public function isWellEquippedForTeaching()
    {
        $teachingScore = 0;
        
        if ($this->has_whiteboards) $teachingScore++;
        if ($this->has_smart_boards) $teachingScore += 2;
        if ($this->has_projectors) $teachingScore++;
        if ($this->has_network_cabling) $teachingScore++;
        
        return $teachingScore >= 3;
    }

    /**
     * Check if floor is well-equipped for offices.
     */
    public function isWellEquippedForOffice()
    {
        $officeScore = 0;
        
        if ($this->has_chairs && $this->has_tables) $officeScore += 2;
        if ($this->has_network_cabling) $officeScore++;
        if ($this->has_telephone_lines) $officeScore++;
        if ($this->has_cctv) $officeScore++;
        
        return $officeScore >= 4;
    }

    /**
     * Get infrastructure score (0-100).
     */
    public function getInfrastructureScoreAttribute()
    {
        $score = 0;
        $maxScore = 20; // 20 points maximum
        
        // Electrical (4 points)
        if ($this->has_backup_generator) $score += 2;
        if ($this->has_ups) $score += 1;
        if ($this->power_sockets_count >= 10) $score += 1;
        
        // Network (4 points)
        if ($this->has_network_cabling) $score += 2;
        if ($this->has_intercom) $score += 1;
        if ($this->has_telephone_lines) $score += 1;
        
        // Security (4 points)
        if ($this->has_cctv) $score += 2;
        if ($this->has_security_desk) $score += 1;
        if ($this->has_access_control) $score += 1;
        
        // Furniture (4 points)
        if ($this->has_chairs && $this->has_tables) $score += 2;
        if ($this->has_whiteboards) $score += 1;
        if ($this->has_smart_boards) $score += 1;
        
        // Special Rooms (4 points)
        if ($this->has_server_room) $score += 1;
        if ($this->has_storage_room) $score += 1;
        if ($this->has_cleaner_room) $score += 1;
        if ($this->has_electric_room) $score += 1;
        
        return round(($score / $maxScore) * 100, 2);
    }
}