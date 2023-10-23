<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class OfficialMemo extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'number',
        'created_by',
        'file_path',
        'created_at'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid7();
        });
    }
}
