<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'label',
        'comment',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'created_by' => 'integer',
        ];
    }
}
