<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        $vendor1 = User::create([
            'email' => 'vendor1@test.com',
            'password' => Hash::make('password'),
        ]);
        $vendor1->assignRole(UserType::getTypeName(UserType::VENDOR));

        $vendor2 = User::create([
            'email' => 'vendor2@test.com',
            'password' => Hash::make('password'),
        ]);
        $vendor2->assignRole(UserType::getTypeName(UserType::VENDOR));

        $buyer = User::create([
            'email' => 'buyer@test.com',
            'password' => Hash::make('password'),
        ]);
        $buyer->assignRole(UserType::getTypeName(UserType::BUYER));
    }
}
