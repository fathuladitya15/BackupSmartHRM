<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    use HasFactory;

    protected $table = 'table_users_logins';

    protected $fillable = [
        'user_id','user_ip','city','country','country_code','longitude','latitude','browser','os'
    ];
}
