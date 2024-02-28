@extends('vBase')
@section('title') {{ 'Data Izin Karyawan' }}@endsection
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Izin  /</span>Data Pengajuan Izin</h4>
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

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>ID Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Alasan</th>
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
                                <tr>
                                    <td></td>
                                    <td style="color: black; text-align:center">Izin (Sakit,Pulang Cepat / Keperluan Lain)</td>
                                    <td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Yang Bertanda Tangan Dibawah Ini : </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">ID Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="id_karyawan" id="id_karyawan"  value="" readonly>

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
                    <br><br>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Hari/ Tanggal</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" name="tanggal" class="form-control" id="tanggal" required readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jam Keluar</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="time" class="form-control" name="jam_keluar"  id="jam_keluar"  required readonly>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jam Masuk</label>
                            <input type="time" class="form-control" name="jam_masuk"  id="jam_masuk" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class=" col-form-label" for="basic-default-name">Jelaskan</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea class="form-control" required="required" name="alasan" id="alasan"  readonly></textarea>

                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="row" >
                        <div class="form-group col-md-12">
                            <label class=" col-form-label" for="basic-default-name" style="float: right" id="tanggal_pembuatan">Sukabumi,
                        </label>

                        </div>
                    </div>
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
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <p>
                                Catatan : Untuk sakit atau yang lainnya diwajibakan memberikan informasi terlebih dahulu pada Koodinator sekertariat PT PFI dan Leader
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <p>
                                Petunjuk pengisian form Jam : <br>
                                *) AM Berlaku mulai dari 00.00 WIB sampai dengan 11:59 WIB. <br>
                                *) PM Berlaku mulai dari 12.00 WIB sampai dengan 23.59 WIB. <br>
                            </p>
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

<div class="modal fade" id="modalUpload" role="dialog"  >
    <div class="modal-dialog  modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Upload File Izin </h5>
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
                            <input type="hidden" name="id_upload" value="" id="id_upload">
                        <input type="file" class="form-control" name="file_izin" required>
                        </div>
                    </div>
                    <br><br>
                    <button type="submit" style="float: right" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalView" role="dialog"  >
    <div class="modal-dialog  modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">Upload File Izin </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form >
                    <div class="col-lg-12" style="text-align: center;">
                        <img src="" alt="" id="image_file_izin" style="width: 50%">
                    </div>
                    <div class="col-lg-12">
                        <iframe src="" width="100%" height="500px" id="pdf_file_izin" frameborder="0"></iframe>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('izin-data-admin-korlap') }}";
    var url_save    = "{{ route('izin-save') }}";
    var url_detail  = "{{ route('izin-detail') }}";
    var url_upload  = "{{ route('izin-upload') }}";
    var url_files   = "{{ route('izin-file') }}";
    var assets      = "{{ asset('') }}";
</script>
<script src="{{ asset('assets/js/admin_korlap/izin_aio.js') }}"></script>
@endpush

