<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
      use HasFactory;

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'duration',
        'stipend',
        'application_deadline',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

     public function applications()
    {
        return $this->hasMany(Application::class, 'internship_id');
    }
}
