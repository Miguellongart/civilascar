<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Miguel Longart",
            "email" => "longart86@gmail.com",
            "dni" => "95944181",
            "password" => Hash::make("12345678"),
        ])->assignRole('admin');

        User::create([
            'name' => 'civil ascar',
            'email' => 'ascarcivil@gmail.com',
            'dni' => '12345678',
            'password' => Hash::make('ascarcivil.2024!') 
        ])->assignRole('admin');
    
        User::create([
            'name' => 'Luis Ascar',
            'email' => 'profesorsaldelacama@gmail.com',
            'dni' => '87654321',
            'password' => Hash::make('ascarcivil.2024!')
        ])->assignRole('admin');
    }
}
