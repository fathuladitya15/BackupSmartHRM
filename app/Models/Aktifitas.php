<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktifitas extends Model
{
    use HasFactory;

    protected $table = 'table_aktivitas';

    protected $fillable = [
        'id_user','aktivitas','id_client'
    ];
}
