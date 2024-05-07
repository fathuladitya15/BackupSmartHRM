<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAtt extends Model
{
    use HasFactory;

    protected $table = 'request_atts';
    protected $fillable = [
        'list_request_atts_id',
        'id_karyawan',
        'id_client',
    ];
}
