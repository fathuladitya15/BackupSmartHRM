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

        // USER MEGASARI

        User::create([
            'username' => 'mamat',
            'name' => 'Mamat Alkatiri',
            'email' => 'mmt12@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'karyawan',
            'id_client' => 2,
            'id_karyawan' => '22001'
        ]);
        User::create([
            'username' => 'rasya',
            'name' => 'Rasya Bayu Pamungkas',
            'email' => 'rrrr@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'admin',
            'id_client' => 2,
            'id_karyawan' => '1999123'
        ]);


        // USER AIO SUKABUMI
        User::create([
            'username' => 'rehan',
            'name' => 'REHAN ERLANGGA',
            'email' => 'raihan@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'admin',
            'id_client' => 3,
            'id_karyawan' => 'PFI00123'
        ]);
        User::create([
            'username' => 'achmad',
            'name' => 'Achmad Siswanto',
            'email' => 'achmd@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'karyawan',
            'id_client' => 3,
            'id_karyawan' => 'B0483'
        ]);


        // USER HRD
        User::create([
            'username'  => 'dwi',
            'name'      => 'Dwi',
            'email'     => 'ack1asd23@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'hrd',
            'id_client' => 1,
            'id_karyawan' => 'PFI1001'
        ]);

        // DIREKTUR MPO
        User::create([
            'username'  => 'rommy',
            'name'      => 'Rommy Ghannny',
            'email'     => 'rmm12@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'direktur',
            'id_client' => 1,
            'id_karyawan' => 'DR1001'
        ]);

        // DIREKTUR HRD
        User::create([
            'username'  => 'direktur_hrd',
            'name'      => 'Direktur HRD',
            'email'     => 'testingmail@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'direktur',
            'id_client' => 1,
            'id_karyawan' => 'PFI18723'
        ]);
    }
}
