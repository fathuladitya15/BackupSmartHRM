@extends('vBase')
@section('title') {{  'Surat Peringatan' }}@endsection
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Surat /</span>Peringatan</h4>
<div class="row">
    <div class="col-md-6" style="">
        <div class="card bg-success">
            <div class="card-body">
                <h5 >Surat Peringatan</h5>
                <h3 >{{ $total_surat }} Surat Peringatan</h3>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning" >
            <div class="card-body">
                <h5 >Menunggu Persetujuan</h5>
                <h3 >{{ $total_menuggu }} Permintaan</h3>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">

            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Surat Peringatan </h5>
            </div>
            @if ( in_array(Auth::user()->roles,['admin','korlap']))
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Lembur
                </button>
            </div>

            @endif
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                          <tr class="text-nowrap">
                            <th>#</th>
                            <th>ID Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Divisi</th>
                            <th>Lokasi Kerja</th>
                            <th>Peringatan</th>
                            <th>Masa Berlaku</th>
                            <th>Sampai dengan</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal</th>
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
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Lembur</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id_peringatan" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="id_karyawan">Pilih Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control" id="id_karyawan" value="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="no_sp">No SP</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Nomor Surat" name="no_sp" type="text" value="" readonly id="no_sp">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="jabatan">Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control" value="" readonly name="jabatan" id="jabatan">

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Lokasi Kerja</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text"  required="required" name="lokasi_kerja" id="lokasi_kerja" value="" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Masa Berlaku</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control"  name="mulai_dari" id="mulai_dari"  required="required">

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Sampai Dengan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control"  name="sampai_dengan" id="sampai_dengan" value="" required="required">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-form-label">Peringatan Ke </label>
                            <span class="text-danger pl-1">*</span>
                            <select name="tipe_peringatan" id="tipe_peringatan" class="form-control" required="required">
                                <option value="">-- Peringatan Ke -- </option>
                                <option value="1">Pertama</option>
                                <option value="2">Kedua</option>
                                <option value="3">Ketiga</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nama Pemberi</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control"  name="nama_pemberi" id="nama_pemberi">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="col-form-label"> Alasan Alasan Peringatan</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea name="alasan_peringatan" id="alasan_peringatan" class="form-control" cols="30" rows="10"></textarea>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <hr>
                        <h5 style="color:black">Keterangan</h5>
                        <hr>
                        <p>
                            Peringatan ini berlaku selama 3 bulan,
                            <span id="tanggal_start"> ... </span> s/d
                            <span id="tanggal_end"> ... </span>.
                            Apabila Mengulangi Pelanggaran Yang Sama Dalam Kurun Waktu Sama Berlakunya Sanksi
                            Disiplin, Akan Dikenakan Sanksi Lebih Tinggi Dari Sebelumnya
                            (Sesuai Peraturan PT Proven Force Indonseia)
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="col-form-label">Tanggal</label>
                            @php
                                $date = Carbon\Carbon::now()->format('Y-m-d');
                            @endphp
                            <input type="date" name="tanggal_pembuatan_sp" class="form-control" value="{{ $date }}" disabled>
                        </div>
                    </div>
                    <br><br>
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
                        <button type="submit" class="btn btn-success">Setujui</button>
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
    var  url_karyawan = "{{ route('peringatan-detail-karyawan') }}" ;
    var  url_get_karyawan = "{{ route('peringatan-data-karyawan') }}";
    var  url_save   = "{{ route('peringatan-save') }}";
    var  url_data   = "{{ route('peringatan-data-spv') }}";
    var  url_detail = "{{ route('peringatan-detail') }}";
</script>
<script src="{{ asset('assets/js/supervisor/peringatan.js') }}"></script>
@endpush
