<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    function __contstruct() {
        $this->middleware("auth");
    }

    function index() {
        dd("INDEX ABSENSI");
    }
}
