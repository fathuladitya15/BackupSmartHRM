<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::create([
            'type' => 'Shift',
            'ke'   => 1,
            'id_client'   => 2,
            'waktu_mulai' => '07.00',
            'waktu_selesai' => '15.00',
        ]);
        Shift::create([
            'type' => 'Shift',
            'id_client'   => 2,
            'ke'   => 2,
            'waktu_mulai' => '15.00',
            'waktu_selesai' => '23.00',
        ]);
        Shift::create([
            'type' => 'Shift',
            'id_client'   => 2,
            'ke'   => 3,
            'waktu_mulai' => '23.00',
            'waktu_selesai' => '15.00',
        ]);

        Shift::create([
            'type' => 'Long',
            'id_client'   => 2,
            'ke'   => 1,
            'waktu_mulai' => '07.00',
            'waktu_selesai' => '15.00',
        ]);
        Shift::create([
            'type' => 'Long',
            'id_client'   => 2,
            'ke'   => 2,
            'waktu_mulai' => '19.00',
            'waktu_selesai' => '23.00',
        ]);

        Shift::create([
            'type' => 'Shift',
            'ke'   => 1,
            'id_client'   => 3,
            'waktu_mulai' => '07.00',
            'waktu_selesai' => '15.00',
        ]);
        Shift::create([
            'type' => 'Shift',
            'id_client'   => 2,
            'ke'   => 3,
            'waktu_mulai' => '15.00',
            'waktu_selesai' => '23.00',
        ]);

    }
}
