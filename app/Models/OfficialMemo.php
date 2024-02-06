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
    public $timestamps = true;

    protected $fillable = [
        'title',
        'number',
        'created_by',
        'file_path',
        'work_unit',
        'created_at'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid7();
        });
    }
}
