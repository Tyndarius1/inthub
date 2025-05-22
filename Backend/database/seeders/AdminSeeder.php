<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        User::create(
            [
                'name' => 'Admin',
                'email' => 'kb.dacera@mlgcl.edu.ph',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
