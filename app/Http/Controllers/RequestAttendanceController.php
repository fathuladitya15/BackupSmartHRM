<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DataTables;
use Auth;
use Illuminate\Http\Request;
use App\Models\RequestAttendace;
use App\Models\Karyawan;

class RequestAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Auth::user()->roles;

        if($roles == 'karyawan') {
            return view('layouts.request_absensi.karyawan_external');
        }else if(in_array($roles,['kr-project','kr-pusat'])) {
            return view('layouts.request_absensi.karyawan_internal');
        }else if($roles == 'manajer') {
            return view('layouts.request_absensi.manager');
        }else if($roles == 'direktur') {
            $divisi = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first()->divisi()->first()->nama_divisi;
            if($divisi == 'MPO') {
                return view('layouts.request_absensi.manager');
            }else {
                abort(401);
            }
        }else {
            abort(404);
        }
    }

    function data(Request $request) {
        $id_client      = Auth::user()->id_client;
        $id_karyawan    = Auth::user()->id_karyawan;
        $roles          = Auth::user()->roles;
        $kategori       = Karyawan::where('id_karyawan',$id_karyawan)->first()->kategori;

        if($roles == 'karyawan') {
            $data = RequestAttendace::where('id_karyawan',$id_karyawan)->orderBy('created_at','DESC')->get();
        }else {
            $data  = [];
        }
        $dt  = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('tanggal', function($row) {
            $translated = Carbon::parse($row->request_date)->translatedFormat('l,d F Y');
            return $translated;
        })
        ->addColumn('jam',function ($row) {
            $translated = Carbon::parse($row->request_time)->TranslatedFormat('H:i');
            return $translated;
        })
        ->addColumn('status', function($row) {
            $status = $row->status;
            if($status == 0) {
                $r = '<span class="btn btn-warning btn-sm">Menunggu disetujui</span>';
            }else {
                $r = '<span class="btn btn-danger btn-sm">Err</span>';
            }
            return $r;
        })
        ->rawColumns(['tanggal','jam','status'])
        ->make(true);
        return $dt;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
