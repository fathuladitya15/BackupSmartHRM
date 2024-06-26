@extends('vBase')
@section('title') {{ 'Data Pengajuan Izin Anda' }}@endsection
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
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-error alert-dismissible fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Izin </h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Izin
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>tanggal</th>
                                <th>jam keluar</th>
                                <th>jam kembali</th>
                                <th>Keterangan keperluan</th>
                                <th>Status</th>
                                <th>Disetujui Oleh</th>
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Izin</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_izin" id="id_izin" value="">
                <div class="modal-body">
                    <div class="row">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td width="100">  <img src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" alt="" width="90"> </td>
                                    <td style="color: black; text-align:center">PT Proven Force Indonesia</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="color: black; text-align:center">Surat Izin Keluar</td>
                                    <td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">ID Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="id_karyawan" id="id_karyawan"  value="{{ Auth::user()->id_karyawan }}" readonly>

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
                            <input class="form-control" required="required" name="jabatan" id="jabatan" type="text" value="{{ $jabatan->nama_jabatan }}" readonly>

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="divisi" id="divisi" type="text" value="{{ $divisi->nama_divisi }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class=" col-form-label" for="basic-default-name">Keterangan Keperluan</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea class="form-control" required="required" name="alasan" id="alasan" ></textarea>

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label class=" col-form-label" for="basic-default-name">Jam Keluar</label>
                            <span class="text-danger pl-1">*</span>

                        </div>
                        <div class="form-group col-md-4">
                            <input class="form-control" required="required"  name="jam_keluar" id="jam_keluar" type="time" >
                        </div>
                        <div class="form-group col-md-5">
                            <div class="d-flex radio-check">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_kembali" value="0" onclick="handleClick(this);" checked name="kembali" class="form-check-input" required="required">
                                    <label class="form-check-label " for="g_kembali">Kembali</label>
                                </div>
                                <div class="custom-control custom-radio ms-1 custom-control-inline" style="padding-left: 35px;">
                                    <input type="radio" id="g_tidak_kembali" value="1" onclick="handleClick(this);" name="kembali" class="form-check-input">
                                    <label class="form-check-label " for="g_tidak_kembali">Tidak Kembali</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" id="jam_kembali">
                        <div class="form-group col-md-2">
                            <label class=" col-form-label" for="basic-default-name">Jam Kembali</label>
                            <span class="text-danger pl-1">*</span>

                        </div>
                        <div class="form-group col-md-4">
                            <input class="form-control" required="required"  name="jam_masuk" id="jam_masuk" type="time" >
                        </div>
                        <div class="form-group col-md-2">
                            <label class=" col-form-label" for="basic-default-name">Tanggal</label>
                            <span class="text-danger pl-1">*</span>
                        </div>
                        <div class="form-group col-md-4">
                            <input class="form-control" required="required"  name="tanggal" id="tanggal" type="date" >
                        </div>
                    </div>
                    <br>
                    {{-- Tanda Tangan --}}
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                            <a href="javascript:void(0)" onclick="ttd('{{ Auth::user()->id_karyawan }}')" class="btn btn-primary btn-sm" id="button_ttd">Tanda Tangani</a>
                            <img class="image_ttd" src="" alt="" id="image_ttd" width="100"> <br  />
                            <div class="" style="">
                                <p id=""> {{ Str::title(Auth::user()->name) }} </p>
                            </div>
                            <input type="hidden" name="ttd" value="" id="ttd" required >
                        </div>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('izin-data') }}";
    var url_save    = "{{ route('izin-save') }}";
    var url_detail  = "{{ route('izin-detail') }}";
    var assets      = "{{ asset('') }}";
</script>
<script src="{{ asset('assets/js/izin_pfi.js') }}"></script>
@endpush
