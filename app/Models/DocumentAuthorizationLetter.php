<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentAuthorizationLetter extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = ['title', 'number', 'contract_number', 'payment_total', 'vendor_id', 'work_unit', 'created_by', 'vendor_name', 'bank_name', 'account_number', 'vendor_id', 'file_path', 'created_at','payment_number'];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'name');
    }
}
