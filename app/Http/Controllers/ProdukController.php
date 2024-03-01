<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    function index() {
        return view("vError");
        // dd(Auth::user()->id_client);
    }
}
