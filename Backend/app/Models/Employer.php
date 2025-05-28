<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Employer extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'company_name',
        'email',
        'password',
        'industry',
        'description',
        'website',
        'phone',
        'location',
        'contact_person',
        'company_pic',
        'otp',              
        'otp_expires_at',   
        'is_verified',
        'reset_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'reset_token',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];
}
