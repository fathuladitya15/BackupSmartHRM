<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run() {
        $superadmin = User::create([
            'name' => 'mfa',
            'username' => 'mfa',
            'email' => 'email@mail.com',
            'kategori_k' => 'pusat',
            'password' => Hash::make('password'),
        ]);

        $karyawan_project = User::create([
            'name' => 'karyawan Project',
            'username' => 'karyawan_project',
            'email' => 'emailkaryawaaaaaaa@mail.com',
            'password' => Hash::make('password'),
        ]);

        $karyawan_internal = User::create([
            'name' => 'karyawan Internal Pusat',
            'username' => 'internal_pusat',
            'kategori_k' => 'pusat',
            'email' => 'emailkary12344awa@mail.com',
            'password' => Hash::make('password'),
        ]);

        $karyawan_internal_project = User::create([
            'name' => 'karyawan Internal Project',
            'username' => 'internal_project',
            'kategori_k' => 'project',
            'email' => 'emailkaryasdawa@mail.com',
            'password' => Hash::make('password'),
        ]);

        $manager = User::create([
            'name' => 'Manager',
            'username' => 'manager',
            'email' => 'manahasder@mail.com',
            'password' => Hash::make('password'),
        ]);

        $superadmin->assignRole(['superadmin','karyawan']);
        // $superadmin->assignRole('karyawan');

        $manager->assignRole('manager');
        // $manager->assignRole('karyawan');

        $karyawan_project->assignRole('karyawan');
     }
    // public function run(): void
    // {

    //     $SuperAdmiin = User::create([
    //         'username' => 'mfa',
    //         'name' => 'fathul',
    //         'email' => 'Email@gmail.com',
    //         'password' => Hash::make('password'),
    //         'roles' => 'superadmin',
    //     ]);

    //     $karyawan_megasari  = User::create([
    //         'username' => 'mamat',
    //         'name' => 'Mamat Alkatiri',
    //         'email' => 'mmt12@gmail.com',
    //         'password' => Hash::make('password'),
    //         'roles' => 'karyawan',
    //         'id_client' => 2,
    //         'id_karyawan' => '22001'
    //     ]);
    //     $admin_megasari     = User::create([
    //         'username' => 'rasya',
    //         'name' => 'Rasya Bayu Pamungkas',
    //         'email' => 'rrrr@gmail.com',
    //         'password' => Hash::make('password'),
    //         'roles' => 'admin',
    //         'id_client' => 2,
    //         'id_karyawan' => '1999123'
    //     ]);

    //     $admin_AIO_sukabumi     =   User::create([
    //                                     'username' => 'rehan',
    //                                     'name' => 'REHAN ERLANGGA',
    //                                     'email' => 'raihan@gmail.com',
    //                                     'password' => Hash::make('password'),
    //                                     'roles' => 'admin',
    //                                     'id_client' => 3,
    //                                     'id_karyawan' => 'PFI00123'
    //                                 ]);
    //     $karyawan_AIO_sukabumi  =   User::create([
    //         'username' => 'achmad',
    //         'name' => 'Achmad Siswanto',
    //         'email' => 'achmd@gmail.com',
    //         'password' => Hash::make('password'),
    //         'roles' => 'karyawan',
    //         'id_client' => 3,
    //         'id_karyawan' => 'B0483'
    //     ]);

    //     $spv_hrd = User::create([
    //         'username'  => 'dwi',
    //         'name'      => 'Dwi',
    //         'email'     => 'ack1asd23@gmail.com',
    //         'password'  => Hash::make('password'),
    //         'roles'     => 'hrd',
    //         'id_client' => 1,
    //         'id_karyawan' => 'PFI1001'
    //     ]);

    //     $dirut_MPO = User::create([
    //         'username'  => 'rommy',
    //         'name'      => 'Rommy Ghannny',
    //         'email'     => 'rmm12@gmail.com',
    //         'password'  => Hash::make('password'),
    //         'roles'     => 'direktur',
    //         'id_client' => 1,
    //         'id_karyawan' => 'DR1001'
    //     ]);

    //     $dirut_HRD = User::create([
    //             'username'  => 'direktur_hrd',
    //             'name'      => 'Direktur HRD',
    //             'email'     => 'testingmail@gmail.com',
    //             'password'  => Hash::make('password'),
    //             'roles'     => 'direktur',
    //             'id_client' => 1,
    //             'id_karyawan' => 'PFI18723'
    //         ]);

    //     $manager_Finance   =    User::create([
    //         'username'  => 'manager_finance',
    //         'name'      => 'MANAGER FINANCE',
    //         'email'     => 'testingm12ail@gmail.com',
    //         'password'  => Hash::make('password'),
    //         'roles'     => 'manajer',
    //         'id_client' => 1,
    //         'id_karyawan' => 'PFI18723111'
    //     ]);


    // }
}
