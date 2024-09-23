<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => (string) Str::uuid(),
            'lastName' => 'Fisher',
            'otherNames' => 'Floyd',
            'email' => 'floyd@example.com',
            'phoneNumber' => '0123456789',
            'password' => Hash::make('admin1234567'),
            'role' => 'administrator',
        ]);
    }
}
