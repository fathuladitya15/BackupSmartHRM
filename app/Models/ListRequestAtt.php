<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListRequestAtt extends Model
{
    use HasFactory;

    protected $table = 'list_request_atts';

    protected $fillable = [
        'date_att',
        'id_client',
        'created_by',
    ];
}
