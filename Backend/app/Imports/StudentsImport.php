<?php
namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Student([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'dob' => $row['dob'],
            'gender' => $row['gender'],
            'phone' => $this->formatPhone($row['phone']),
            'address' => $row['address'],
            'school' => $row['school'],
            'course' => $row['course'],
            'year_level' => $row['year_level'],
            'student_id_number' => $row['student_id_number'],
            'is_verified' => false,
        ]);
    }

    private function formatPhone($phone)
    {

        return preg_match('/^0/', $phone) ? preg_replace('/^0/', '+63', $phone) : $phone;
    }
}

