<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DataTables;
use Auth;
use Illuminate\Http\Request;
use App\Models\RequestAttendace;
use App\Models\Karyawan;
use App\Models\Shift;
use App\Models\User;

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
        }else if(in_array($roles,['manajer','direktur'])) {

            if($roles == 'direktur') {
                $divisi = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first()->divisi()->first()->nama_divisi;
                if($divisi == 'MPO') {
                    return view('layouts.request_absensi.manager');
                }else {
                    abort(401);
                }
            }
            return view('layouts.request_absensi.manager');

        }
        else if(in_array($roles,['admin','korlap'])) {
            $dateNow        = Carbon::now()->translatedFormat('l,d F Y');
            return view('layouts.request_absensi.admin_korlap',compact('dateNow'));
        }
        else {
            abort(404);
        }
    }

    function data(Request $request) {
        $id_client      = Auth::user()->id_client;
        $id_karyawan    = Auth::user()->id_karyawan;
        $roles          = Auth::user()->roles;
        $kategori       = Karyawan::where('id_karyawan',$id_karyawan)->first()->kategori;
        $dateNow        = Carbon::now()->format('Y-m-d');

        if($roles == 'karyawan') {
            $data = RequestAttendace::where('id_karyawan',$id_karyawan)->orderBy('created_at','DESC')->get();
        }
        else if(in_array($roles,['admin','korlap'])) {
            $dataMaster = RequestAttendace::where('request_date',$dateNow)->where('id_client',Auth::user()->id_client);
            $sNamaKaryawan = $request->get('nama_karyawan');
            $sFromDate     = $request->get('from_date');
            $sToDate       = $request->get('to_date');

            if($sNamaKaryawan == null && $sFromDate == null && $sToDate == null) {
                $dataS = $dataMaster;
            }
            if($sNamaKaryawan != null ) {
                $dataS = $dataMaster->where('id_karyawan',$sNamaKaryawan);
            }

            if($sFromDate != null){
                $dataS = $dataMaster->where('request_date','>=',$sFromDate);
            }

            if($sToDate != null ) {
                $dataS = $dataMaster->where('request_date','<=',$sToDate);
            }
            $data = $dataS->orderBy('created_at','DESC')->get();
        }
        else {
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
            ->addColumn('status', function($row) use ($roles) {
                $status = $row->status;
                $r = $this->status($status,$roles);

                return $r;
            })
            ->addColumn('divisi', function($row) {
                $divisi = Karyawan::where('id_karyawan',$row->id_karyawan)->first()->divisi()->first()->nama_divisi;
                return $divisi;
            })
            ->addColumn('jabatan', function($row) {
                $jabatan = Karyawan::where('id_karyawan',$row->id_karyawan)->first()->jabatan()->first()->nama_jabatan;
                return $jabatan;
            })
            ->addColumn('nama_karyawan',function($row) {
                $nama_karyawan = Karyawan::where('id_karyawan',$row->id_karyawan)->first()->nama_karyawan;

                return $nama_karyawan;
            })
            ->addColumn('shift',function($row) {
                if($row->shift != null) {
                    $shift = Shift::find($row->shift);
                    return $shift->type.' '.$shift->ke;
                }else {
                    return "";
                }
            })
            ->rawColumns(['tanggal','jam','status'])
            ->make(true);
        return $dt;
    }

    function status($status,$roles) {
        if($status == 0) {
            $r = '<span class="btn btn-warning btn-sm">Menunggu disetujui</span>';
        }else if($status == 1) {
            $r = '<span class="btn btn-success btn-sm">Disetujui</span>';
        }else if($status == 2) {
            $r = '<span class="btn btn-danger btn-sm">Ditolak</span>';
        }else {
            $r = '';
        }
        // if($roles == 'karyawan') {
        //     }
        // }else if(in_array($roles,['admin','korlap','karya'])) {
        //     $r = '';
        // }

        return $r;
    }

    function getDataKaryawan(Request $request) {
        $searchTerm     = $request->input('q');
        $id_client      = Auth::user()->id_client;
        $id_karyawan    = Auth::user()->id_karyawan;
        $roles          = Auth::user()->roles;

        if(in_array($roles,['admin','korlap'])) {
            $dataMaster           = User::where('id_client',$id_client)->where('roles','karyawan')->get();
        }else {
            $data = "";
        }

        if ($searchTerm) {
            $r = $dataMaster->where('name','like',"%$searchTerm%")->orWhere('id_karyawan','like',"%$searchTerm%")->get();
        } else {
            $r = $dataMaster;
        }

        return response()->json($r);



        // $result = [];

        // foreach ($data as $key) {
        //     $result[] = [
        //         'nama_karyawan' => $user
        //     ]
        // }
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
