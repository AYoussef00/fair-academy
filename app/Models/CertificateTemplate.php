<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'background',
    ];

    public function studentCertificates(): HasMany
    {
        return $this->hasMany(StudentCertificate::class);
    }
}
