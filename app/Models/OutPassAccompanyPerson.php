<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutPassAccompanyPerson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'out_pass_id',
        'name',
        'mobile',
        'email',
        'relationship',
        'id_proof_type',
        'id_proof_number',
        'photo',
    ];

    /**
     * Get the out pass that owns the accompany person.
     */
    public function outPass(): BelongsTo
    {
        return $this->belongsTo(OutPass::class);
    }

    /**
     * Get photo URL or default.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    /**
     * Get full name with relationship.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->relationship})";
    }
}