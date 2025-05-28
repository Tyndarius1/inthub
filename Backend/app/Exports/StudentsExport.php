<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         return Student::select(
            'name', 'email', 'password', 'dob', 'gender', 'phone', 'address',
            'school', 'course', 'year_level', 'student_id_number', 'student_pic'
        )->get();
    }


     public function headings(): array
    {
        return [
            'Name', 'Email', 'Password', 'DOB', 'Gender', 'Phone', 'Address',
            'School', 'Course', 'Year Level', 'Student ID Number', 'Student Pic'
        ];
    }
}
