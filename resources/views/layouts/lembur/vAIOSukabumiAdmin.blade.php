@extends('vBase')
@section('title') {{  'Lembur - Admin / Korlap' }}@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}">

<style>
    .black {
        color: black;

    }
    h4 {
        color: white;
    }
    h3 {
        color: white;
    }
    h5 {
        color: white;
    }
    .card-title {
        padding-right: 30px;
        border-left-width: 30px;
        padding-left: 30px;

    }
    .card-header {
        background-color:#47b4f5;
        margin-bottom: 20px;
    }

    .swal2-container {
        z-index: 999999;
    };
    p#nama_karyawan {
        margin-top: -50px !important;
    }
    p#nama_korlap {
        margin-top: -50px !important;
    }
    .over_text {
        margin-top: -50px !important;
    }
    #button_ttd{
        width: 150px;
    }
</style>

@endpush

@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lembur /</span>Data Lembur</h4>

<div class="row">
    <div class="col-md-6" style="">
        <div class="card bg-info">
            <div class="card-body">
                <h5>Lembur Karyawan</h5>
                <h3>{{ $kr_lembur }} Karyawan</h3>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning">
            <div class="card-body">
                <h5>Menunggu Persetujuan</h5>
                <h3> {{ $wait_lembur }} Permintaan</h3>
            </div>
        </div>
    </div>
</div>
<br><br>

<div class="row">
    <div class="col-xxl">
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Lembur</h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Filter" style="float: left">
                    <i class='bx bx-filter'></i> Filter Tanggal
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Download" style="float: right">
                    <i class='bx bx-download' ></i> Download
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>ID Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>Shift</th>
                            <th>status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Filter" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  >
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Filter Data Lembur </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="" class="col-form-label">Mulai Dari Tanggal</label>
                        <input type="date" name="from_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">Sampai Dengan Tanggal</label>
                        <input type="date" name="to_date" class="form-control" required>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-2">
                        <a href="javascript:void(0)" onclick="filter()"  class="cari btn btn-primary">Cari</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="Download" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  >
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Download Data Lembur Karyawan </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form id="downloadDataLembur" action="{{ route("lembur-download-multiple",['hash' => HashVariable(Auth::user()->id_karyawan)]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="col-form-label">Nama Karyawan</label>
                            <select name="id_karyawan" id="id_karyawab" class="form-control" required>
                                <option value="">-- Pilih Nama Karyawan --</option>
                                @foreach ($daftar_kr as $item)
                                    <option value="{{ $item->id_karyawan }}">{{ $item->name }} - {{ $item->id_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="col-form-label">Mulai Dari Tanggal</label>
                            <input type="date" name="from_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="col-form-label">Sampai Dengan Tanggal</label>
                            <input type="date" name="to_date" class="form-control" required>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Download</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle" style="color: black">Buat Data </h5>
          <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" id="Add" >
            @csrf
            <input type="hidden" name="id_lembur" id="id_lembur" value="">
            <div class="modal-body">
                <div class="row">
                    <table class="table ">
                        <tbody>
                            <tr>
                                <td width="100">  <img src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" alt="" width="90"> </td>
                                <td style="color: black; text-align:center">PT Proven Force Indonesia</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="color: black; text-align:center">Form Intruksi Lembur</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">ID Karyawan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required" name="id_karyawan" id="id_karyawan" type="text" value="" readonly>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Nama Karyawan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="nama_karyawan" id="nama_karyawan" type="text" value="" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required" name="jabatan" id="jabatan" type="text" value="" readonly>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Divisi</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="divisi" id="divisi" type="text" value="" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Shift</label>
                        <span class="text-danger pl-1">*</span>
                        {{-- <select name="shift" id="shift" class="form-control" {{  $get_shift ? 'readonly' : '' }}> --}}
                            <select name="shift" id="shift" class="form-control">
                            <option value="">-- Pilih Shift --</option>
                            @foreach ($shift as $item)
                                <option value="{{ $item->id }}" >{{ $item->type }} - {{ $item->ke }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Tanggal</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="tanggal_lembur" id="tanggal_lembur" type="date">
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class=" col-form-label" for="">Aktivitas Reguler</label>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for="">Mulai Jam</label>
                        <span class="text-danger pl-1">*</span>
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" required="required" name="jam_mulai_ar" id="jam_mulai_ar" type="time" value="" >
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 10px;">
                        <p style="text-align: center;">s / d</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for=""> Jam</label>
                        <span class="text-danger pl-1">*</span>
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" required="required" name="jam_selesai_ar" id="jam_selesai_ar" type="time" value="" >
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class=" col-form-label" for="">Rencana Lembur</label>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for="">Mulai Jam</label>
                        <span class="text-danger pl-1">*</span>
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" required="required" name="jam_mulai_rl" id="jam_mulai_rl" type="time" value="" >
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 10px;">
                        <p style="text-align: center;">s / d</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for=""> Jam</label>
                        <span class="text-danger pl-1">*</span>
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" required="required" name="jam_selesai_rl" id="jam_selesai_rl" type="time" value="" >
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class=" col-form-label" for="">Lembur Aktual</label>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for="">Mulai Jam</label>
                        <span class="text-danger pl-1">*</span>
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" required="required" name="jam_mulai_la" id="jam_mulai_la" type="time" value="" >
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 10px;">
                        <p style="text-align: center;">s / d</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for=""> Jam</label>
                        <span class="text-danger pl-1">*</span>
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" required="required" name="jam_selesai_la" id="jam_selesai_la" type="time" value="" >
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Rencana Aktivitas</label>
                        <span class="text-danger pl-1">*</span>
                        <textarea name="alasan_lembur" id="alasan_lembur" cols="30" rows="10" required class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Aktual  Aktifitas</label>
                        <span class="text-danger pl-1">*</span>
                        <textarea name="tugas" id="tugas" cols="30" rows="10" required class="form-control"></textarea>
                    </div>
                </div>
                <br><br><br>
                <div class="row">
                    <div class="col-lg-6">
                        <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                        <img class="image_ttd" src="" alt="" id="image_ttd_kr" width="100"> <br  />
                        <div class="over_text" style="">
                            <p id="nama_ttd_karyawan">tes</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                        <a href="javascript:void(0)" onclick="ttd('{{ Auth::user()->id_karyawan }}')" class="btn btn-primary btn-sm" id="button_ttd">Tanda Tangani</a>
                        <img class="image_ttd" src="" alt="" id="image_ttd" width="100"> <br  />
                        <div class="" style="">
                            <p id="">{{ Auth::user()->name }}</p>
                        </div>
                        <input type="hidden" name="ttd" value="" id="ttd" required >
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="aksi">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- MODAL UPLOAD FILE -->
<div class="modal fade" id="modalUpload" role="dialog"  >
    <div class="modal-dialog  modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Upload Surat Lembur </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formUpload" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="col-form-label">Upload file yang sudah ditanda tangani</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="id_karyawan" value="" id="id_karyawan_upload">
                            <input type="hidden" name="id_lembur" value="" id="id_lembur_upload">
                        <input type="file" class="form-control" name="file_lembur" required>

                        </div>
                    </div>
                    <br><br>
                    <button type="submit" style="float: right" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /MODAL UPLOAD FILE -->

<!-- MODAL VIEW FILE -->
<div class="modal fade" id="modalView" role="dialog"  >
    <div class="modal-dialog  modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Detail File </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form >
                    <div class="col-lg-12" style="text-align: center;">
                        <img src="" alt="" id="image_file_lembur" style="width: 50%">
                    </div>
                    <div class="col-lg-12">
                        <iframe src="" width="100%" height="500px" id="pdf_file_lembur" frameborder="0"></iframe>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /MODAL VIEW FILE -->

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('lembur-admin-korlap',['hash' => HashVariable(Auth::user()->id_karyawan)]) }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var url_save    = "{{ route('lembur-save') }}";
    var url_upload  = "{{ route('lembur-upload') }}";
    var url_view    = "{{ route('lembur-get-files') }}";
    var asset     = "{{ URL::to('') }}";
    var url_send  = "{{ route('lembur-kirim-files') }}";

</script>
<script src="{{ asset('assets/js/admin_korlap/lembur_AIO.js') }}"></script>

@endpush
