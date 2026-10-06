<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutPassPassenger extends Model
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
        'phone',
        'relation',
        'id_proof_type',
        'id_proof_number',
        'age',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
    ];

    /**
     * Get the out pass that owns the passenger.
     */
    public function outPass(): BelongsTo
    {
        return $this->belongsTo(OutPass::class);
    }

    /**
     * Get full name with relation.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->relation})";
    }
}