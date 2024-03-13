@extends('vBase')
@section('title') {{ 'Referensi Kerja' }} @endsection
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Surat /</span>Referensi Kerja</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Surat Referensi Kerja </h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Referensi Kerja
                </button>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                              <th>#</th>
                              <th>No Arsip</th>
                              <th>No.Surat</th>
                              <th>ID Karyawan</th>
                              <th>Nama Karyawan</th>
                              <th>Awal Masuk</th>
                              <th>Akhir Masuk</th>
                              <th>Tanggal Pembuatan</th>
                              <th>Keterangan</th>
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
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Referensi Kerja</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_rf" value="" id="id_rf">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="no_arsip">No Arsip</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Nomor Arsip" name="no_arsip" type="text" value="" id="no_arsip" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="no_sp">No Surat</label>
                            {{-- <span class="text-danger pl-1">*</span> --}}
                            <input class="form-control" placeholder="Nomor Surat" name="no_sp" type="text" value="" id="no_sp" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="periode">Periode</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Periode" name="periode" type="text" value="" id="periode" >
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="tahun">tahun</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control" value="" required value="Masukan Tahun Terakhir" name="tahun" id="tahun">

                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="id_karyawan">Pilih Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <div id="id_karyawan_detail" style="display:none;">
                                <input type="text" id="id_kr_detail" value="" class="form-control">
                            </div>
                            <div id="select_karyawan">
                                <select name="id_karyawan" id="id_karyawan" class="form-control" required>
                                    <option value="">-- Pilih Karyawan-- </option>
                                    @foreach ($karyawan as $item)
                                        <option value="{{ $item->id_karyawan }}">{{ $item->id_karyawan }} - {{ $item->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="nik">Nomor Induk Kependudukan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control"  name="nik" id="nik"  required="required" readonly>

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="jabatan">Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control"  name="jabatan" id="jabatan" value="" required="required" readonly>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="col-form-label" for="alamat">alamat </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" name="alamat" id="alamat" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-form-label">Awal Masuk </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" name="awal_masuk" id="awal_masuk" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Akhir Masuk</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" name="akhir_masuk" id="akhir_masuk" class="form-control" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-form-label"> Tanggal Pembuatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" name="tanggal_pembuatan" id="tanggal_pembuatan" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-form-label"> Keterangan</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="keterangan" id="keterangan" class="form-control">
                                <option value=""> -- Pilih Keterangan -- </option>
                                <option value="1">Mengundurkan Diri</option>
                                <option value="2">Habis Kontrak</option>
                                <option value="3">Berkelakuan Buruk</option>
                            </select>
                        </div>

                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_save          = "{{ route('save-rf') }}";
    var url_data          = "{{ route('data-rf') }}";
    var url_get_karyawan = "{{ route('peringatan-data-karyawan') }}";

</script>
<script src="{{ asset('assets/js/admin_korlap/referensi_kerja.js') }}"></script>

@endpush
