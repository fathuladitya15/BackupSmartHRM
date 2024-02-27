<?php

namespace App\Http\Controllers;


use DB;
use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Shift;
use App\Models\Divisi;
use App\Models\Lembur;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    // DATA KARYAWAN UNTUK SUPERADMIN
    function data_karyawan_superadmin(Request $request) {
        $data = DB::table('table_karyawan as tk')
            ->join('users as us','us.id_karyawan','=','tk.id_karyawan')
            ->where('us.roles','!=','karyawan')
            ->get();

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('join_date',function($row) {
                return Carbon::parse($row->join_date)->translatedFormat('d F Y');
            })
            ->addColumn('end_date',function($row) {
                return Carbon::parse($row->end_date)->translatedFormat('d F Y');
            })
            ->addColumn('face_id',function($row) {
                return "";
            })
            ->addColumn('jabatan',function($row) {
                return Jabatan::find($row->jabatan)->nama_jabatan;
            })
            ->addColumn('divisi',function($row) {
                return Divisi::find($row->divisi)->nama_divisi;
            })
            ->addColumn('photo',function($row) {
                return "";
            })
            ->addColumn('aksi',function($row) {
                $edit   = '<a href="'.route('karyawan-edit',['hash' => HashVariable($row->id_karyawan)]).'" class="btn btn-primary btn-sm"  ><i class="bx bx-edit-alt"></i>Edit</a>';
                return $edit;
            })
            ->addColumn('disetujui_oleh',function($row) {
                return "";
            })
            ->addColumn('disetujui_pada',function($row) {
                return "";
            })
            ->addColumn('status',function($row) {
                if($row->status == 0) {
                    $st =  "<span class='badge bg-success'>Aktif</>";
                }else {
                    $st =  "<span class='badge bg-danger'>Tidak aktif</>";
                }
                return $st;
            })
            ->rawColumns(['aksi','face_id','photo','disetujui_oleh','disetujui_pada','status','join_date','end_date'])
            ->make(true);

        return $dataTable;

    }

    // DATA KARYAWAN UNTUK HRD
    function data_karyawan_hrd(Request $request) {
        $data = DB::table('table_karyawan as tk')->select('tk.*','ad.nama_divisi','aj.nama_jabatan','us.name','us.roles')
            ->join('users as us','us.id_karyawan','=','tk.id_karyawan')
            ->join('table_divisi as ad','ad.id','=','tk.divisi')
            ->join('table_jabatan as aj','aj.id','=','tk.jabatan')
            ->where('us.roles','!=','karyawan')
            ->where('us.id','!=',Auth::user()->id)
            ->get();

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kategori',function($row) {
                return $row->roles;
            })
            ->addColumn('join_date',function($row) {
                return Carbon::parse($row->join_date)->translatedFormat('d F Y');
            })
            ->addColumn('end_date',function($row) {
                return Carbon::parse($row->end_date)->translatedFormat('d F Y');
            })
            ->addColumn('face_id',function($row) {
                return "";
            })
            ->addColumn('photo',function($row) {
                return "";
            })
            ->addColumn('aksi',function($row) {
                return "";
            })
            ->addColumn('status',function($row) {
                return "";
            })
            ->rawColumns(['aksi','face_id','photo','status','join_date','end_date','kategori'])
            ->make(true);

        return $dataTable;

    }

    // DATA KARYAWAN UNTUK ADMIN/KORLAP
    function data_karyawan_admin(Request $request) {
        $data = DB::table('table_karyawan as tk')
            ->join('users as us','us.id_karyawan','=','tk.id_karyawan')
            ->where('us.id_client',Auth::user()->id_client)
            ->where('us.roles','karyawan')
            ->get();
            // dd($data);

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('join_date',function($row) {
                return Carbon::parse($row->join_date)->translatedFormat('d F Y');
            })
            ->addColumn('end_date',function($row) {
                return Carbon::parse($row->end_date)->translatedFormat('d F Y');
            })
            ->addColumn('face_id',function($row) {
                return "";
            })
            ->addColumn('photo',function($row) {
                $count = Filemanager::where('id_karyawan',$row->id_karyawan)->where('slug','foto_profile')->count();
                if($count >= 1 )
                {
                    $data_profile = Filemanager::where('id_karyawan',$row->id_karyawan)->where('slug','foto_profile')->first();
                    $path = asset($data_profile->path.$data_profile->filename) ;
                }else {
                    $jk = $row->jenis_kelamin;
                    if($jk == 'L') {
                        $path = asset('assets/img/avatars/1.png');
                    }else {
                        $path = asset('assets/img/avatars/6.png');
                    }
                }
                $image = '<img src='.$path.' alt="Gambar" class="circular_image" style="align-content: center" />';
                return $image;
            })
            ->addColumn('aksi',function($row) {
                $edit   = '<a href="'.route('karyawan-edit',['hash' => HashVariable($row->id_karyawan)]).'" class="btn btn-primary btn-sm"  ><i class="bx bx-edit-alt"></i>Edit</a>';
                $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';
                return $edit.'&nbsp;'.$hapus;
            })
            ->addColumn('nama_divisi',function($row) {
                return Divisi::find($row->divisi)->nama_divisi;
            })
            ->addColumn('nama_jabatan',function($row) {
                return Jabatan::find($row->jabatan)->nama_jabatan;
            })
            ->addColumn('disetujui_oleh',function($row) {
                return "";
            })
            ->addColumn('disetujui_pada',function($row) {
                return "";
            })
            ->addColumn('status_karyawan',function($row) {
                if($row->status_karyawan == 0) {
                    $sts = "<span class='badge bg-warning'> Sedang direview </span>";
                }else if($row->status_karyawan == 1) {
                    $sts = "<span class='badge bg-success'> Approved </span>";
                }else if($row->status_karyawan == 2) {
                    $sts = "<span class='badge bg-danger'> Reject </span>";

                }
                return $sts;
            })
            ->addColumn('status',function($row) {
                if($row->status == 0) {
                    $sts = "<span class='badge bg-success'> Aktif </span>";
                }else if($row->status == 1) {
                    $sts = "<span class='badge bg-success'> Tidak Aktif </span>";
                }else {
                    $sts = "<span class='badge bg-danger'> Error </span>";

                }
                return "";
            })
            ->rawColumns(['aksi','face_id','photo','disetujui_oleh','disetujui_pada','status','join_date','end_date','nama_divisi','nama_jabatan','status_karyawan'])
            ->make(true);

        return $dataTable;

    }

    // DATA LEMBUR UNTUK KARYAWAN
    function data_lembur_karyawan(Request $request) {

        $data     = Lembur::where('id_karyawan',Auth::user()->id_karyawan)->where('id_client',Auth::user()->id_client)->get();

        $dt = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
            $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="detail('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Edit</a>';
            $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';

            if(Auth::user()->id_client == 1) {
                if(in_array($row->status,[0,1,2])) {
                    return "";
                }else {
                    $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';
                    return $file.'&nbsp;'.$hapus;
                }
            }else if(in_array(Auth::user()->id_client,[3,4])) {
                if($row->status == 0) {
                    $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';
                    return $file.'&nbsp;'.$hapus;
                }elseif ($row->status == 4){
                    $view_f   = '<a href="javascript:void(0)" style=" padding-right: 20px;"  onclick="view('.$row->id.')" id="view_'.$row->id.'" class="btn btn-info btn-actions btn-sm" ><i class="menu-icon tf-icons bx bx-folder-open"></i>Lihat File</a> &nbsp;';
                    return $view_f;
                }else {
                    return "";
                }

            }else {
                if($row->status >= '1') {
                    $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';
                    return $file.'&nbsp;'.$hapus;
                }else {
                    return $edit.'&nbsp;'.$hapus;
                }

            }

        })
        ->addColumn('status', function($row) {
            if (Auth::user()->id_client == 1){
                if (Auth::user()->id_client == 1){
                    if($row->status == 0 ) {
                        $st = "<span class='badge bg-warning'> Menuggu Persetujuan  Manager Divisi</span>";
                    }else if($row->status == 1) {
                        $st = "<span class='badge bg-warning'> Menuggu Persetujuan  HRD</span>";
                    }else if($row->status == 2 ){
                        $st = "<span class='badge bg-warning'> Menuggu Tanda Tangan Direktur</span>";
                    }
                    else if($row->status == 3) {
                        $st = "<span class='badge bg-success'> Telah disetujui </span>";
                    }
                }
            }else if(in_array(Auth::user()->id_client,[3,4])) {
                if($row->status != 4 ) {
                    $st = "<span class='badge bg-warning'> Menuggu Persetujuan </span>";
                }else if($row->status == 4) {
                    $st = "<span class='badge bg-success'> Telah disetujui </span>";
                }
            }else {
                if($row->status == 0) {
                    $st = "<span class='badge bg-warning'> Menuggu Persetujuan </span>";
                }else if($row->status == 1) {
                    $st = "<span class='badge bg-success'> Telah disetujui </span>";
                }else {
                    $st = "<span class='badge bg-success'> Telah disetujui </span>";
                }

            }
            return $st;
        })
        ->addColumn('disetujui_oleh',function($row) {
            $data = Filemanager::where('path',$row->ttd_admin_korlap)->where("slug",'signature');
            if($data->count() != 0) {
                $id_karyawan = $data->first()->id_karyawan;
                $nama_admin = User::where('id_karyawan',$id_karyawan)->first()->name;
            }else {
                $nama_admin = "";
            }
            return $nama_admin;
        })
        ->addColumn("tanggal_lembur", function($row) {
            return Carbon::parse($row->tanggal_lembur)->translatedFormat('d F Y');
        })
        ->addColumn('id_shift',function($row) {
            if(Auth::user()->id_client == 3) {
                $shift  = Shift::find($row->id_shift);

                $nama_shift = $shift->type.' '.$shift->ke;
                return $nama_shift;

            }else {
                return "";
            }
        })
        ->rawColumns(['aksi','status','disetujui_oleh','nama_shift'])
        ->make(true);

        return $dt;
    }

    // DATA LEMBUR UNTUK ADMIN/KORLAP
    function data_lembur_karyawan_admin(Request $request) {
        $dataMaster     = Lembur::where('id_client',Auth::user()->id_client);

        $dataKaryawan   = Karyawan::where("id_karyawan",Auth::user()->id_karyawan)->first();
        $divisi         = Divisi::find($dataKaryawan->divisi)->nama_divisi;


        if(Auth::user()->roles == 'manajer'){
            $data = $dataMaster->where('status','>=','0')->where('divisi',$divisi)->get();
        }else if(Auth::user()->roles == 'hrd') {
            $data = $dataMaster->orWhere('divisi',['MPO'])->where('status','>=','1')->get();
        }
        else if(Auth::user()->roles == 'direktur') {
            if($divisi == 'MPO'){
                $data =  Lembur::where('divisi','MPO')->where('status','>=','0')->get();
                // $data = $dataMaster->where('status','>=','0')->where('divisi',$divisi)->get();
            }else {
                $data = Lembur::where('status','>=','2')->get();
            }
        }else {
            $data = $dataMaster->where('id_karyawan','!=',Auth::user()->id_karyawan)->get();
        }

        if($request->filled('from_date') || $request->filled('to_date')){
            $data = $data->whereBetween('tanggal_lembur', [$request->from_date, $request->to_date]);
        }
        $dt = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="detail('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Detail</a>';
                $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';
                if(Auth::user()->id_client == 1) {
                    $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-danger btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';

                    if($row->status == 3){
                        return $file;
                    }else if(Auth::user()->roles == 'manajer' ) {
                        if($row->status != 0) {
                            return "";
                        }else {
                            return $edit;
                        }
                    }else if(Auth::user()->roles == 'hrd') {
                        if($row->status != 1) {
                            return "";
                        }else {
                            return $edit;
                        };
                    }else if(Auth::user()->roles == 'direktur') {
                        $dataKaryawan   = Karyawan::where("id_karyawan",Auth::user()->id_karyawan)->first();
                        $divisi         = Divisi::find($dataKaryawan->divisi)->nama_divisi;
                        if($divisi == 'MPO'){
                            if($row->status != 0) {
                                return "";
                            }else {
                                return $edit;
                            }
                        }else {
                            if($row->status != 2) {
                                return "";
                            }else {
                                return $edit;
                            };
                        }
                    }else {
                        return $edit.'&nbsp;'.$file;
                    }
                }else if(in_array(Auth::user()->id_client,[3,4])) {

                    $file       = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-danger btn-sm" ><i class="bx bx-download"></i> Download</a>';
                    $upload   = '<a href="javascript:void(0)" style=" padding-right: 20px;"  onclick="upload('.$row->id.','."'".$row->id_karyawan."'".')" class="upload btn btn-warning btn-actions btn-sm" ><i class="menu-icon tf-icons bx bx-upload"></i>Upload</a> &nbsp;';
                    $view_f   = '<a href="javascript:void(0)" style=" padding-right: 20px;"  onclick="view('.$row->id.')" id="view_'.$row->id.'" class="btn btn-info btn-actions btn-sm" ><i class="menu-icon tf-icons bx bx-folder-open"></i>Lihat File</a> &nbsp;';
                    $send_f   = '<a href="javascript:void(0)" style=" padding-right: 20px;"  onclick="send('.$row->id.')" id="send_'.$row->id.'" class="btn btn-primary btn-actions btn-sm" ><i class="menu-icon tf-icons bx bx-mail-send"></i>Setujui dan Kirim File</a> &nbsp;';
                    if($row->status == '1') {
                        return $file;
                    }else if($row->status == '2') {
                        return $file.'&nbsp;'.$upload;
                    }else if($row->status == '3'){
                        return $view_f.'&nbsp;'.$send_f;
                    }else if($row->status == '4'){
                        return $view_f;
                    }else {
                        return $edit.'&nbsp'.$view_f;
                    }

                }else {
                    if($row->status == '1') {
                        $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-danger btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';
                        return $file;
                    }else {
                        return $edit;
                    }

                }
            })
            ->addColumn('status', function($row) {
                if (Auth::user()->id_client == 1){
                    $wait           = "<span class='badge bg-warning'> Menuggu Persetujuan Manager</span>";
                    $waitHRD        = "<span class='badge bg-warning'> Menuggu Persetujuan HRD </span>";
                    $waitDirektur   = "<span class='badge bg-warning'> Menuggu Persetujuan Direktur </span>";
                    if($row->status == 0 ) {
                        $st = $wait;
                    }else if($row->status == 1 ) {
                        $st = $waitHRD;
                    }else if($row->status == 2) {
                        $st = $waitDirektur;
                    }else {
                        $st = "<span class='badge bg-success'> Telah disetujui </span>";
                    }
                }else if(in_array(Auth::user()->id_client,[3,4])) {
                    if($row->status == 0) {
                    $st = "<span class='badge bg-warning'> Perlu ditandatangani </span>";
                    }else if($row->status == 1) {
                        $st = "<span class='badge bg-info'> Download File </span>";
                    }else if($row->status == 2){
                        $st = "<span class='badge bg-info'> Upload File </span>";
                    }else if($row->status == 4){
                        $st = "<span class='badge bg-success'> Telah disetujui </span>";
                    }else {
                        $st = "<span class='badge bg-warning'> Menuggu Persetujuan </span>";
                    }

                }else {
                    if($row->status == 0) {
                        $st = "<span class='badge bg-warning'> Perlu ditandatangani </span>";
                    }else if($row->status == 1) {
                        $st = "<span class='badge bg-success'> Telah disetujui </span>";
                    }

                }
                return $st;
            })
            ->addColumn("tanggal_lembur", function($row) {
                return Carbon::parse($row->tanggal_lembur)->translatedFormat('d F Y');
            })
            ->addColumn('id_shift',function($row) {
                if(Auth::user()->id_client == 3) {
                    $shift  = Shift::find($row->id_shift);

                    $nama_shift = $shift->type.' '.$shift->ke;
                    return $nama_shift;

                }else {
                    return "";
                }
            })
            ->rawColumns(['aksi','status','disetujui_oleh','id_shift'])
            ->make(true);

            return $dt;
    }

    // DATA LEMBUR UNTUK SUPERVISOR
    function data_lembur_karyawan_spv(Request $request) {
        $dataMaster = Lembur::where('id_client',Auth::user()->id_client);

        if(Auth::user()->id_client == "3"){
            $data = $dataMaster->where('status',4)->get();

        }else {
            $data = $dataMaster->where('status',1)->get();
        }
        if($request->filled('from_date') || $request->filled('to_date')){
            $data = $data->whereBetween('tanggal_lembur', [$request->from_date, $request->to_date]);
        }
        $dt = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $edit   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" ><i class="bx bx-edit-alt"></i>Detail</a>';
                $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';
                $view_f   = '<a href="javascript:void(0)" style=" padding-right: 20px;"  onclick="view('.$row->id.')" id="view_'.$row->id.'" class="btn btn-info btn-actions btn-sm" ><i class="menu-icon tf-icons bx bx-folder-open"></i>Lihat File</a> &nbsp;';

               if(Auth::user()->id_client == 3) {
                return $view_f;
               }else {
                   return $edit;

               }
            })
            ->addColumn('status', function($row) {
                if(Auth::user()->id_client == "3"){

                    if($row->status == 0) {
                        $st = "<span class='badge bg-warning'> Perlu ditandatangani </span>";
                    }else if($row->status == 1) {
                        $st = "<span class='badge bg-success'> Telah disetujui Admin Korlap </span>";
                    }else {
                        $st = "";
                    }
                }
                return $st;
            })
            ->addColumn("tanggal_lembur", function($row) {
                return Carbon::parse($row->tanggal_lembur)->translatedFormat('d F Y');
            })
            ->rawColumns(['aksi','status','disetujui_oleh'])
            ->make(true);

            return $dt;
    }

    // DATA LEMBUR UNTUK MANAGER
    function data_lembur_karyawan_manajer(Request $request) {
        $data = Lembur::where('id_client',Auth::user()->id_client)->where('status',1)->get();

        if($request->filled('from_date') || $request->filled('to_date')){
            $data = $data->whereBetween('tanggal_lembur', [$request->from_date, $request->to_date]);
        }
        $dt = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $edit   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" ><i class="bx bx-edit-alt"></i>Detail</a>';
                $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';
                return $edit;
            })
            ->addColumn('status', function($row) {
                if($row->status == 0) {
                    $st = "<span class='badge bg-warning'> Perlu ditandatangani </span>";
                }else if($row->status == 1) {
                    $st = "<span class='badge bg-success'> Telah disetujui Admin Korlap </span>";
                }
                return $st;
            })
            ->addColumn("tanggal_lembur", function($row) {
                return Carbon::parse($row->tanggal_lembur)->translatedFormat('d F Y');
            })
            ->rawColumns(['aksi','status','disetujui_oleh'])
            ->make(true);

            return $dt;
    }



}
