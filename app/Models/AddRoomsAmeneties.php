<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddRoomsAmeneties extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_amenities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'institute_id',
        'branch_id',
        'room_id',
        
        // Furniture
        'chairs_count',
        'tables_count',
        'desks_count',
        'bookshelves_count',
        'cabinets_count',
        'sofas_count',
        
        // Audio Visual Equipment
        'has_speakers',
        'has_microphone',
        'has_sound_system',
        'has_tv',
        'has_dvd_player',
        'has_video_conferencing',
        
        // Computer Equipment
        'computers_count',
        'printers_count',
        'scanners_count',
        'has_server_rack',
        'has_network_switch',
        'network_ports_count',
        
        // Laboratory Equipment
        'has_lab_equipment',
        'has_fume_hood',
        'has_safety_shower',
        'has_eye_wash_station',
        'has_gas_supply',
        'has_water_supply',
        
        // Special Features
        'has_wheelchair_access',
        'has_braille_signage',
        'has_emergency_lighting',
        'has_backup_power',
        'has_intercom',
        'has_telephone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Boolean casts
        'has_speakers' => 'boolean',
        'has_microphone' => 'boolean',
        'has_sound_system' => 'boolean',
        'has_tv' => 'boolean',
        'has_dvd_player' => 'boolean',
        'has_video_conferencing' => 'boolean',
        'has_server_rack' => 'boolean',
        'has_network_switch' => 'boolean',
        'has_lab_equipment' => 'boolean',
        'has_fume_hood' => 'boolean',
        'has_safety_shower' => 'boolean',
        'has_eye_wash_station' => 'boolean',
        'has_gas_supply' => 'boolean',
        'has_water_supply' => 'boolean',
        'has_wheelchair_access' => 'boolean',
        'has_braille_signage' => 'boolean',
        'has_emergency_lighting' => 'boolean',
        'has_backup_power' => 'boolean',
        'has_intercom' => 'boolean',
        'has_telephone' => 'boolean',
        
        // Integer casts
        'chairs_count' => 'integer',
        'tables_count' => 'integer',
        'desks_count' => 'integer',
        'bookshelves_count' => 'integer',
        'cabinets_count' => 'integer',
        'sofas_count' => 'integer',
        'computers_count' => 'integer',
        'printers_count' => 'integer',
        'scanners_count' => 'integer',
        'network_ports_count' => 'integer',
        
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
        'chairs_count' => 0,
        'tables_count' => 0,
        'desks_count' => 0,
        'bookshelves_count' => 0,
        'cabinets_count' => 0,
        'sofas_count' => 0,
        'has_speakers' => false,
        'has_microphone' => false,
        'has_sound_system' => false,
        'has_tv' => false,
        'has_dvd_player' => false,
        'has_video_conferencing' => false,
        'computers_count' => 0,
        'printers_count' => 0,
        'scanners_count' => 0,
        'has_server_rack' => false,
        'has_network_switch' => false,
        'network_ports_count' => 0,
        'has_lab_equipment' => false,
        'has_fume_hood' => false,
        'has_safety_shower' => false,
        'has_eye_wash_station' => false,
        'has_gas_supply' => false,
        'has_water_supply' => false,
        'has_wheelchair_access' => false,
        'has_braille_signage' => false,
        'has_emergency_lighting' => false,
        'has_backup_power' => false,
        'has_intercom' => false,
        'has_telephone' => false,
    ];

    /**
     * Get the institute that owns the room amenity.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the room amenity.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the room that owns the amenity.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Scope a query to only include amenities with video conferencing.
     */
    public function scopeWithVideoConferencing($query)
    {
        return $query->where('has_video_conferencing', true);
    }

    /**
     * Scope a query to only include amenities with wheelchair access.
     */
    public function scopeWithWheelchairAccess($query)
    {
        return $query->where('has_wheelchair_access', true);
    }

    /**
     * Scope a query to only include amenities with lab equipment.
     */
    public function scopeWithLabEquipment($query)
    {
        return $query->where('has_lab_equipment', true);
    }

    /**
     * Scope a query to only include amenities with backup power.
     */
    public function scopeWithBackupPower($query)
    {
        return $query->where('has_backup_power', true);
    }

    /**
     * Scope a query to only include amenities with computers.
     */
    public function scopeWithComputers($query)
    {
        return $query->where('computers_count', '>', 0);
    }

    /**
     * Scope a query to only include amenities with audio visual equipment.
     */
    public function scopeWithAudioVisual($query)
    {
        return $query->where(function ($query) {
            $query->where('has_speakers', true)
                  ->orWhere('has_sound_system', true)
                  ->orWhere('has_tv', true);
        });
    }

    /**
     * Get total furniture count.
     */
    public function getTotalFurnitureAttribute()
    {
        return $this->chairs_count + 
               $this->tables_count + 
               $this->desks_count + 
               $this->bookshelves_count + 
               $this->cabinets_count + 
               $this->sofas_count;
    }

    /**
     * Get total computer equipment count.
     */
    public function getTotalComputerEquipmentAttribute()
    {
        return $this->computers_count + 
               $this->printers_count + 
               $this->scanners_count;
    }

    /**
     * Check if room has basic furniture.
     */
    public function hasBasicFurniture()
    {
        return $this->chairs_count > 0 && $this->tables_count > 0;
    }

    /**
     * Check if room has basic computer setup.
     */
    public function hasBasicComputerSetup()
    {
        return $this->computers_count > 0 && $this->network_ports_count > 0;
    }

    /**
     * Check if room has basic lab safety.
     */
    public function hasBasicLabSafety()
    {
        return $this->has_safety_shower || $this->has_eye_wash_station || $this->has_fume_hood;
    }

    /**
     * Check if room has accessibility features.
     */
    public function hasAccessibilityFeatures()
    {
        return $this->has_wheelchair_access || $this->has_braille_signage;
    }

    /**
     * Check if room is suitable for conferences.
     */
    public function isSuitableForConferences()
    {
        return $this->has_sound_system || 
               $this->has_microphone || 
               $this->has_video_conferencing ||
               $this->has_speakers;
    }

    /**
     * Check if room is suitable for computer lab.
     */
    public function isSuitableForComputerLab()
    {
        return $this->computers_count >= 10 && 
               $this->network_ports_count >= 10 &&
               $this->has_server_rack;
    }

    /**
     * Check if room is suitable for science lab.
     */
    public function isSuitableForScienceLab()
    {
        return $this->has_lab_equipment && 
               ($this->has_gas_supply || $this->has_water_supply) &&
               $this->has_basic_lab_safety;
    }

    /**
     * Check if room is suitable for office.
     */
    public function isSuitableForOffice()
    {
        return $this->desks_count > 0 && 
               $this->chairs_count > 0 &&
               ($this->has_intercom || $this->has_telephone);
    }

    /**
     * Get all furniture items as array.
     */
    public function getFurnitureItemsAttribute()
    {
        $items = [];
        
        if ($this->chairs_count > 0) {
            $items[] = "Chairs ({$this->chairs_count})";
        }
        
        if ($this->tables_count > 0) {
            $items[] = "Tables ({$this->tables_count})";
        }
        
        if ($this->desks_count > 0) {
            $items[] = "Desks ({$this->desks_count})";
        }
        
        if ($this->bookshelves_count > 0) {
            $items[] = "Bookshelves ({$this->bookshelves_count})";
        }
        
        if ($this->cabinets_count > 0) {
            $items[] = "Cabinets ({$this->cabinets_count})";
        }
        
        if ($this->sofas_count > 0) {
            $items[] = "Sofas ({$this->sofas_count})";
        }
        
        return $items;
    }

    /**
     * Get all audio visual equipment as array.
     */
    public function getAudioVisualEquipmentAttribute()
    {
        $equipment = [];
        
        if ($this->has_speakers) {
            $equipment[] = 'Speakers';
        }
        
        if ($this->has_microphone) {
            $equipment[] = 'Microphone';
        }
        
        if ($this->has_sound_system) {
            $equipment[] = 'Sound System';
        }
        
        if ($this->has_tv) {
            $equipment[] = 'TV';
        }
        
        if ($this->has_dvd_player) {
            $equipment[] = 'DVD Player';
        }
        
        if ($this->has_video_conferencing) {
            $equipment[] = 'Video Conferencing';
        }
        
        return $equipment;
    }

    /**
     * Get all computer equipment as array.
     */
    public function getComputerEquipmentAttribute()
    {
        $equipment = [];
        
        if ($this->computers_count > 0) {
            $equipment[] = "Computers ({$this->computers_count})";
        }
        
        if ($this->printers_count > 0) {
            $equipment[] = "Printers ({$this->printers_count})";
        }
        
        if ($this->scanners_count > 0) {
            $equipment[] = "Scanners ({$this->scanners_count})";
        }
        
        if ($this->has_server_rack) {
            $equipment[] = 'Server Rack';
        }
        
        if ($this->has_network_switch) {
            $equipment[] = 'Network Switch';
        }
        
        if ($this->network_ports_count > 0) {
            $equipment[] = "Network Ports ({$this->network_ports_count})";
        }
        
        return $equipment;
    }

    /**
     * Get all lab equipment as array.
     */
    public function getLabEquipmentAttribute()
    {
        $equipment = [];
        
        if ($this->has_lab_equipment) {
            $equipment[] = 'Laboratory Equipment';
        }
        
        if ($this->has_fume_hood) {
            $equipment[] = 'Fume Hood';
        }
        
        if ($this->has_safety_shower) {
            $equipment[] = 'Safety Shower';
        }
        
        if ($this->has_eye_wash_station) {
            $equipment[] = 'Eye Wash Station';
        }
        
        if ($this->has_gas_supply) {
            $equipment[] = 'Gas Supply';
        }
        
        if ($this->has_water_supply) {
            $equipment[] = 'Water Supply';
        }
        
        return $equipment;
    }

    /**
     * Get all special features as array.
     */
    public function getSpecialFeaturesAttribute()
    {
        $features = [];
        
        if ($this->has_wheelchair_access) {
            $features[] = 'Wheelchair Access';
        }
        
        if ($this->has_braille_signage) {
            $features[] = 'Braille Signage';
        }
        
        if ($this->has_emergency_lighting) {
            $features[] = 'Emergency Lighting';
        }
        
        if ($this->has_backup_power) {
            $features[] = 'Backup Power';
        }
        
        if ($this->has_intercom) {
            $features[] = 'Intercom';
        }
        
        if ($this->has_telephone) {
            $features[] = 'Telephone';
        }
        
        return $features;
    }

    /**
     * Get all amenities grouped by category.
     */
    public function getAllAmenitiesByCategoryAttribute()
    {
        return [
            'furniture' => $this->furniture_items,
            'audio_visual' => $this->audio_visual_equipment,
            'computer' => $this->computer_equipment,
            'lab' => $this->lab_equipment,
            'special_features' => $this->special_features,
        ];
    }

    /**
     * Get seating capacity based on chairs count.
     */
    public function getSeatingCapacityAttribute()
    {
        $capacity = $this->chairs_count;
        
        // Add sofa capacity (assuming 2-3 people per sofa)
        $capacity += ($this->sofas_count * 2);
        
        return $capacity;
    }

    /**
     * Get workstation count.
     */
    public function getWorkstationCountAttribute()
    {
        // A workstation typically has 1 desk and 1 chair
        $workstationsByDesks = $this->desks_count;
        $workstationsByChairs = floor($this->chairs_count / 1);
        
        return min($workstationsByDesks, $workstationsByChairs);
    }

    /**
     * Calculate furniture adequacy score (0-100).
     */
    public function getFurnitureAdequacyScoreAttribute()
    {
        if (!$this->room) {
            return 0;
        }
        
        $requiredChairs = $this->room->capacity ?? 0;
        $requiredTables = ceil($requiredChairs / 4); // 4 chairs per table
        
        $score = 0;
        $maxScore = 10;
        
        // Chairs adequacy (5 points)
        if ($requiredChairs > 0) {
            $chairRatio = min($this->chairs_count / $requiredChairs, 1);
            $score += ($chairRatio * 5);
        }
        
        // Tables adequacy (3 points)
        if ($requiredTables > 0) {
            $tableRatio = min($this->tables_count / $requiredTables, 1);
            $score += ($tableRatio * 3);
        }
        
        // Storage adequacy (2 points)
        if ($this->bookshelves_count > 0 || $this->cabinets_count > 0) {
            $score += 2;
        }
        
        return round(($score / $maxScore) * 100, 2);
    }

    /**
     * Calculate equipment score based on room type.
     */
    public function getEquipmentScoreAttribute()
    {
        if (!$this->room) {
            return 0;
        }
        
        $score = 0;
        $maxScore = 15;
        
        switch ($this->room->room_type) {
            case 'classroom':
                // Classroom needs: AV equipment, furniture
                if ($this->has_speakers || $this->has_sound_system) $score += 3;
                if ($this->has_tv || $this->has_projector) $score += 3;
                if ($this->hasBasicFurniture()) $score += 4;
                if ($this->network_ports_count > 0) $score += 2;
                if ($this->has_wheelchair_access) $score += 1;
                if ($this->has_emergency_lighting) $score += 2;
                break;
                
            case 'lab':
                // Lab needs: lab equipment, safety features
                if ($this->has_lab_equipment) $score += 3;
                if ($this->has_fume_hood) $score += 2;
                if ($this->hasBasicLabSafety()) $score += 4;
                if ($this->has_gas_supply || $this->has_water_supply) $score += 2;
                if ($this->computers_count > 0) $score += 2;
                if ($this->has_emergency_lighting) $score += 2;
                break;
                
            case 'office':
                // Office needs: computers, communication, furniture
                if ($this->computers_count > 0) $score += 3;
                if ($this->has_telephone || $this->has_intercom) $score += 2;
                if ($this->hasBasicFurniture()) $score += 3;
                if ($this->has_server_rack) $score += 1;
                if ($this->printers_count > 0) $score += 2;
                if ($this->has_wheelchair_access) $score += 2;
                if ($this->has_backup_power) $score += 2;
                break;
                
            case 'conference':
                // Conference needs: AV, communication, furniture
                if ($this->isSuitableForConferences()) $score += 5;
                if ($this->has_video_conferencing) $score += 3;
                if ($this->hasBasicFurniture()) $score += 3;
                if ($this->has_intercom) $score += 1;
                if ($this->has_wheelchair_access) $score += 1;
                if ($this->has_backup_power) $score += 2;
                break;
                
            case 'library':
                // Library needs: furniture, storage, computers
                if ($this->hasBasicFurniture()) $score += 4;
                if ($this->bookshelves_count > 0) $score += 4;
                if ($this->computers_count > 0) $score += 3;
                if ($this->has_wheelchair_access) $score += 2;
                if ($this->has_emergency_lighting) $score += 2;
                break;
        }
        
        return round(($score / $maxScore) * 100, 2);
    }

    /**
     * Get overall amenities score.
     */
    public function getOverallAmenitiesScoreAttribute()
    {
        $furnitureScore = $this->furniture_adequacy_score;
        $equipmentScore = $this->equipment_score;
        
        // Weighted average: 40% furniture, 60% equipment
        $overallScore = ($furnitureScore * 0.4) + ($equipmentScore * 0.6);
        
        return round($overallScore, 2);
    }

    /**
     * Check if amenities need updating (low score).
     */
    public function needsUpgrade()
    {
        return $this->overall_amenities_score < 60;
    }
}