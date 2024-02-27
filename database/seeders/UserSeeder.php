<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'mfa',
            'name' => 'fathul',
            'email' => 'Email@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'superadmin',
        ]);

        User::create([
            'username' => 'mamat',
            'name' => 'Mamat Alkatiri',
            'email' => 'mmt12@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'karyawan',
            'id_client' => 2,
            'id_karyawan' => '22001'
        ]);

    }
}
