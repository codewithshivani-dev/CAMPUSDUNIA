<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CertificateLog extends Model
{
    use HasFactory;

    protected $table = 'certificate_logs'; 

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'issue_date' => 'date',
        'generated_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the certificate associated with this log
     */
    public function certificate()
    {
        return $this->belongsTo(StudentCertificate::class, 'certificate_number', 'certificate_number')
                    ->where('student_hash_id', $this->student_hash_id);
    }
    
    /**
     * Get the institute associated with this log
     */
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    /**
     * Resolve the active log sequence column in the current schema.
     */
    public function getSequenceColumn()
    {
        if (Schema::hasColumn($this->getTable(), 'generation_sequence')) {
            return 'generation_sequence';
        }

        if (Schema::hasColumn($this->getTable(), 'download_sequence')) {
            return 'download_sequence';
        }

        return null;
    }

    /**
     * Resolve the active log timestamp column in the current schema.
     */
    public function getTimestampColumn()
    {
        if (Schema::hasColumn($this->getTable(), 'generated_at')) {
            return 'generated_at';
        }

        if (Schema::hasColumn($this->getTable(), 'downloaded_at')) {
            return 'downloaded_at';
        }

        return 'created_at';
    }

    /**
     * Scope to get logs by institute
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope to get first generation
     */
    public function scopeFirstGeneration($query)
    {
        return $query->where('generation_sequence', 1);
    }

    /**
     * Scope to get latest generation
     */
    public function scopeLatestGeneration($query)
    {
        $column = (new self())->getSequenceColumn();
        if ($column) {
            return $query->orderBy($column, 'desc');
        }

        return $query->orderBy('id', 'desc');
    }
}