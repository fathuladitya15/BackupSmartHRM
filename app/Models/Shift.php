<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'table_shift';

    protected $fillable = [
        'type',
        'ke',
        'waktu_mulai',
        'waktu_selesai',
        'id_client'
    ];
}
