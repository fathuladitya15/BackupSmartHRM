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
            'username' => 'dwi',
            'name' => 'Dwi',
            'email' => 'dwi@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'hrd',
            'id_client' => '1',
            'id_karyawan' => 'HRD001',
        ]);

        User::create([
            'username' => 'rehan',
            'name' => 'Raihan Erlangga',
            'email' => 'rehan@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'admin-korlap',
            'id_client' => '2',
            'id_karyawan' => 'AK001',
        ]);

        User::create([
            'username' => 'rasya',
            'name' => 'Rasya Bayu Pamungkas',
            'email' => 'rasya@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'spv-internal',
            'id_client' => '2',
            'id_karyawan' => 'SPV001',
        ]);

        User::create([
            'username' => 'mfaskuy123',
            'name' => 'Muhamad Fathul Aditya',
            'email' => 'blackpirates15@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'kr-pusat',
            'id_client' => '1',
            'id_karyawan' => 'KRPST25616',
        ]);
    }
}
