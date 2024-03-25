@extends('vBase')
@section('title') {{ 'Lembur - Karyawan' }} @endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}">

<style>
    .black {
        color: black;

    }
    h4 {
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
    p#nama {
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
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Lemburan </h5>
            </div>
            <div class="card-title">
                <button class="btn btn-primary" id="buat_lemburan" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">Tambah Data</button>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                          <tr class="text-nowrap">
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Rencana Aktifitas</th>
                            <th>Aktual Aktifitas</th>
                            <th>Status</th>
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
                        <input class="form-control" required="required" name="id_karyawan" id="id_karyawan" type="text" value="{{ Auth::user()->id_karyawan }}" readonly>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Nama Karyawan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="nama_karyawan" id="nama_karyawan" type="text" value="{{ Auth::user()->name }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required" name="jabatan" id="jabatan" type="text" value="{{ $jabatan }}" readonly>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Divisi</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="divisi" id="divisi" type="text" value="{{ $divisi }}" readonly>
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
                        {{-- <span class="text-danger pl-1">*</span> --}}
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control"  name="jam_mulai_rl" id="jam_mulai_rl" type="time" value="" >
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 10px;">
                        <p style="text-align: center;">s / d</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label class=" col-form-label" for=""> Jam</label>
                        {{-- <span class="text-danger pl-1">*</span> --}}
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control"  name="jam_selesai_rl" id="jam_selesai_rl" type="time" value="" >
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
                    <div class="col-lg-12">
                        <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                        <a href="javascript:void(0)" onclick="ttd('{{ Auth::user()->id_karyawan }}')" class="btn btn-primary btn-sm" id="button_ttd">Tanda Tangani</a>
                        <img class="image_ttd" src="" alt="" id="image_ttd" width="100"> <br  />
                        <div class="" style="">
                            <p id="">( {{ Auth::user()->name }} )</p>
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
    var url_data    = "{{ route('lembur-data',['hash' => HashVariable(Auth::user()->id_karyawan)]) }}";
    var url_save    = "{{ route('lembur-save') }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var url_view    = "{{ route('lembur-get-files') }}";
    var assets      = "{{ asset('') }}";
    var asset     = "{{ URL::to('') }}";


</script>
<script src="{{ asset('assets/js/lembur_AIO.js') }}"></script>

@endpush
