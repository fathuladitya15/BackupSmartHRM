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

        $SuperAdmin = User::create([
            'username' => 'mfa',
            'name' => 'fathul',
            'email' => 'Email@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'superadmin',
        ]);

        $karyawan_megasari  = User::create([
            'username' => 'mamat',
            'name' => 'Mamat Alkatiri',
            'email' => 'mmt12@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'karyawan',
            'id_client' => 2,
            'id_karyawan' => '22001'
        ]);

        $admin_megasari     = User::create([
            'username' => 'rasya',
            'name' => 'Rasya Bayu Pamungkas',
            'email' => 'rrrr@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'admin',
            'id_client' => 2,
            'id_karyawan' => '1999123'
        ]);

        $admin_AIO_sukabumi     =   User::create([
            'username' => 'rehan',
            'name' => 'REHAN ERLANGGA',
            'email' => 'raihan@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'admin',
            'id_client' => 3,
            'id_karyawan' => 'PFI00123'
        ]);

        $karyawan_AIO_sukabumi  =   User::create([
            'username' => 'achmad',
            'name' => 'Achmad Siswanto',
            'email' => 'achmd@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'karyawan',
            'id_client' => 3,
            'id_karyawan' => 'B0483'
        ]);

        $spv_hrd = User::create([
            'username'  => 'dwi',
            'name'      => 'Dwi',
            'email'     => 'ack1asd23@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'hrd',
            'id_client' => 1,
            'id_karyawan' => 'PFI1001'
        ]);

        $dirut_MPO = User::create([
            'username'  => 'rommy',
            'name'      => 'Rommy Ghannny',
            'email'     => 'rmm12@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'direktur',
            'id_client' => 1,
            'id_karyawan' => 'DR1001'
        ]);

        $dirut_HRD = User::create([
                'username'  => 'direktur_hrd',
                'name'      => 'Direktur HRD',
                'email'     => 'testingmail@gmail.com',
                'password'  => Hash::make('password'),
                'roles'     => 'direktur',
                'id_client' => 1,
                'id_karyawan' => 'PFI18723'
        ]);

        $manager_Finance   =    User::create([
            'username'  => 'manager_finance',
            'name'      => 'MANAGER FINANCE',
            'email'     => 'testingm12ail@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'manajer',
            'id_client' => 1,
            'id_karyawan' => 'PFI18723111'
        ]);



    }
}
