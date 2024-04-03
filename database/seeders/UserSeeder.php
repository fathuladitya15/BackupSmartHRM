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

        $spv_megasari     = User::create([
            'username' => 'agung',
            'name' => 'Agung Priatmojo',
            'email' => 'agung12@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'spv-internal',
            'id_client' => 2,
            'id_karyawan' => 'SPVM001'
        ]);

        $spv_yp     = User::create([
            'username' => 'unyil',
            'name' => 'Allfiansal',
            'email' => 'alfin123@gmail.com',
            'password' => Hash::make('password'),
            'roles' => 'admin',
            'id_client' => 8,
            'id_karyawan' => 'YP00001'
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
            'username'  => 'spv_hrd',
            'name'      => 'Supervisor HRD',
            'email'     => 'ack1asd23@gmail.com',
            'password'  => Hash::make('1234'),
            'roles'     => 'hrd',
            'id_client' => 1,
            'id_karyawan' => 'PFI1001'
        ]);

        $dirut_MPO = User::create([
            'username'  => 'direktur_mpo',
            'name'      => 'Rommy Ghannny',
            'email'     => 'rmm12@gmail.com',
            'password'  => Hash::make('1234'),
            'roles'     => 'direktur',
            'id_client' => 1,
            'id_karyawan' => 'DR1001'
        ]);

        $dirut_HRD = User::create([
                'username'  => 'direktur_hrd',
                'name'      => 'Direktur HRD',
                'email'     => 'testingmail@gmail.com',
                'password'  => Hash::make('1234'),
                'roles'     => 'direktur',
                'id_client' => 1,
                'id_karyawan' => 'PFI18723'
        ]);

        $manager_Finance   =    User::create([
            'username'  => 'mgr_fnc',
            'name'      => 'MANAGER FINANCE',
            'email'     => 'testingm12ail@gmail.com',
            'password'  => Hash::make('password'),
            'roles'     => 'manajer',
            'id_client' => 1,
            'id_karyawan' => 'PFI18723111'
        ]);

        $ga   =    User::create([
            'username'  => 'ga',
            'name'      => 'General Affair',
            'email'     => 'ga1234@gmail.com',
            'password'  => Hash::make('1234'),
            'roles'     => 'general-affair',
            'id_client' => 1,
            'id_karyawan' => 'GA001'
        ]);




        // USER MENGASARI
        $admin_mgs  = User::create([
            'username' => 'adm_mgs',
            'name' => 'Dwi Yuli Handayani',
            'email' => 'dwi123@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'admin',
            'id_client' => 2,
            'id_karyawan' => 'MGS001'
        ]);

        $korlap_mgs  = User::create([
            'username' => 'korlap_mgs',
            'name' => 'Efi Yulfani',
            'email' => 'efii2001@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'korlap',
            'id_client' => 2,
            'id_karyawan' => 'MGS002'
        ]);
        $spv_mgs  = User::create([
            'username' => 'spv_mgs',
            'name' => 'Supervisor Megasari',
            'email' => 'spv_mgs@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'spv-internal',
            'id_client' => 2,
            'id_karyawan' => 'MGS003'
        ]);

        // SENTUL
        $spv_aio_s  = User::create([
            'username' => 'spvaio_sentul',
            'name' => 'Supervisor Sentul',
            'email' => 'spv_setnul@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'spv-internal',
            'id_client' => 2,
            'id_karyawan' => 'SNTL001'
        ]);
        $korlapaio_sentul  = User::create([
            'username' => 'adminaio_sentul',
            'name' => 'Deri Mauladan',
            'email' => 'Derii123@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'korlap',
            'id_client' => 2,
            'id_karyawan' => 'SNTL002'
        ]);
        $adminaio_sentul  = User::create([
            'username' => 'korlapaio_sentul',
            'name' => 'Agus Gunawan',
            'email' => 'admin_aioS@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'korlap',
            'id_client' => 2,
            'id_karyawan' => 'SNTL003'
        ]);
        // SKB
        $spv_aio_s  = User::create([
            'username' => 'spvaio_skb',
            'name' => 'Supervisor Sukabumi',
            'email' => 'spv_skb@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'spv-internal',
            'id_client' => 3,
            'id_karyawan' => 'SKB001'
        ]);
        $adminaio_skb  = User::create([
            'username' => 'adminaio_skb',
            'name' => 'Admin Sukabumi',
            'email' => 'admskb231@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'admin',
            'id_client' => 3,
            'id_karyawan' => 'SKB002'
        ]);
        $korlapaio_skb  = User::create([
            'username' => 'korlapaio_skb',
            'name' => 'Korlap Sukabumi',
            'email' => 'kr_skbS@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'korlap',
            'id_client' => 3,
            'id_karyawan' => 'SKB003'
        ]);
        // YUPI
        // SKB
        $spv_yupi  = User::create([
            'username' => 'spv_yupi',
            'name' => 'Supervisor Yupi',
            'email' => 'spv_yqwekasd@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'spv-internal',
            'id_client' => 8,
            'id_karyawan' => 'YUPI001'
        ]);
        $adm_yupi  = User::create([
            'username' => 'adm_yupi',
            'name' => 'Admin Yupi',
            'email' => 'admskb2asd31@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'admin',
            'id_client' => 8,
            'id_karyawan' => 'YUPI002'
        ]);
        $korlap_yupi  = User::create([
            'username' => 'korlap_yupi',
            'name' => 'Korlap Yupi',
            'email' => 'kr_skassbS@gmail.com',
            'password' => Hash::make('1234'),
            'roles' => 'korlap',
            'id_client' => 8,
            'id_karyawan' => 'YUPI003'
        ]);


    }
}
