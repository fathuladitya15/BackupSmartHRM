<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class RequestAbsensiController extends Controller
{
    function __construct() {
        $this->middleware("auth");
    }

    function list_index() {
        $getRoles   = Auth::user()->roles;
        if($getRoles == 'admin') {
            return view('layouts.admin_korlap.vRequestAbsensi');
        }else {
            abort(404);
        }
    }


}
