<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'session_id',
        'ip_address_value',
    ];

    public function scopeDateFrom($query, string $date)
    {
        return $query->where('created_at', '>=', $date);
    }

    public function scopeDateTo($query, string $date)
    {
        return $query->where('created_at', '<=', $date);
    }

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function () {
            throw new \RuntimeException('Audit logs cannot be deleted.');
        });
    }
}
