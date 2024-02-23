<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $table    = 'table_clients';

    protected $fillable = [
        'nama_client','prov','kota','kec','kel','alamat_detail'
    ] ;
}
