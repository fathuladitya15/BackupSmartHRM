<?php

namespace Database\Seeders;

// use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run() : void {
        $superadmin  = Role::create([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        $karyawan_project  = Role::create([
            'name' => 'karyawan',
            'guard_name' => 'web'
        ]);

        $direktur  = Role::create([
            'name' => 'direktur',
            'guard_name' => 'web'
        ]);

        $manager  = Role::create([
            'name' => 'manager',
            'guard_name' => 'web'
        ]);

        $hrd  = Role::create([
            'name' => 'hrd',
            'guard_name' => 'web'
        ]);

        $spv_internal  = Role::create([
            'name' => 'supervisor-internal',
            'guard_name' => 'web'
        ]);

        $admin  = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);


     }
    // public function run(): void
    // {
    //     Role::create([
    //         'name_role' => "Superadmin",
    //         'slug_role' => "superadmin",
    //     ]);

    //     Role::create([
    //         'name_role' => "Koordinator Lapangan",
    //         'slug_role' => "korlap",
    //     ]);

    //     Role::create([
    //         'name_role' => "Supervisor",
    //         'slug_role' => "spv-internal",
    //     ]);

    //     Role::create([
    //         'name_role' => "Karyawan",
    //         'slug_role' => "karyawan",
    //     ]);

    //     Role::create([
    //         'name_role' => "Karyawan Pusat",
    //         'slug_role' => "kr-pusat",
    //     ]);

    //     Role::create([
    //         'name_role' => "Karyawan Project",
    //         'slug_role' => "kr-project",
    //     ]);

    //     Role::create([
    //         'name_role' => "General Affair",
    //         'slug_role' => "ga",
    //     ]);

    //     Role::create([
    //         'name_role' => "Direktur",
    //         'slug_role' => "direktur",
    //     ]);

    //     Role::create([
    //         'name_role' => "Human Resource Development",
    //         'slug_role' => "hrd",
    //     ]);

    //     Role::create([
    //         'name_role' => "Admin",
    //         'slug_role' => "admin",
    //     ]);

    //     Role::create([
    //         'name_role' => "General Affair",
    //         'slug_role' => "ga",
    //     ]);
    //     Role::create([
    //         'name_role' => "Manager",
    //         'slug_role' => "manajer",
    //     ]);
    // }
}
