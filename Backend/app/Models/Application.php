<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
use HasFactory;

protected $fillable = [
'student_id',
'internship_id',
'status',
'resume_path',
'cv_path',
'additional_notes',
];

public function student()
{
return $this->belongsTo(Student::class);
}

public function internship()
{
return $this->belongsTo(Internship::class);
}
}
