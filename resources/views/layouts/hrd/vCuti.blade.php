@extends('vBase')
@section('title') {{  'Data Kategori' }}@endsection
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

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Cuti /</span>Pengajuan Cuti</h4>
<div class="row">
    <div class="col-md-6" style="">
        <div class="card bg-info">
            <div class="card-body">
                <h5>Cuti Karyawan</h5>
                <h3>{{ $cuti }} Karyawan</h3>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning">
            <div class="card-body">
                <h5>Menunggu Persetujuan</h5>
                <h3> {{ $wait_acc }} Permohonan</h3>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data  Cuti</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>ID Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>mulai tanggal</th>
                            <th>sampai tanggal</th>
                            <th>alasan cuti</th>
                            <th>periode cuti</th>
                            <th>sisa cuti</th>
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
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Detail Cuti</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                <input type="hidden" name="id_cuti" id="id_cuti" value="">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="id_karyawan">Pilih Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" name="id_karyawan" id="id_karyawan" value="" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="divisi">Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Pilih Karyawan terlebih dahulu" name="divisi" type="text" value="" id="divisi" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="jabatan">Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" id="jabatan" name="jabatan" class="form-control" placeholder="Pilih Karyawan terlebih dahulu" value="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="cuti">Periode / Periode Sisa Cuti</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" id="sisa_cuti" name="sisa_cuti" class="form-control" placeholder="Pilih Karyawan terlebih dahulu" value="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="start_date">Mulai Tanggal</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="end_date">Sampai Tanggal</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="" required>
                        </div>
                        <input type="hidden" name="total_cuti_hari" id="total_cuti_hari" value="">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="alasan_cuti" class="col-form-label">Alasan Cuti</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea name="alasan_cuti" id="alasan_cuti" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sisa_cuti" class="col-form-label">Total Cuti/Sisa Cuti Tahun ini</label>
                            <span class="text-danger pl-1"> *</span>
                            <input type="text" class="form-control" name="total_cuti" placeholder="Pilih Karyawan terlebih dahulu" id="total_cuti" value="" readonly>
                        </div>
                        <div class="form-group col-md-6" >
                            <label for="kategori_cuti" class="col-form-label">Kategori Cuti</label>
                            <span class="text-danger pl-1"> *</span>
                            <input type="text" value="" id="kategori_cuti" name="kategori_cuti" class="form-control">

                        </div>
                    </div>
                    <br>
                    <div class="row" id="form-ttd">
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

                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    var url_data       = "{{ route('data-cuti-hrd') }}";
    var url_save       = "{{ route('cuti-save') }}";
    var url_get_detail = "{{ route('cuti-detail') }}";
    var url_detail     = "{{ route('cuti-details-data') }}";
    var url_validate_jumlah_cuti = "{{ route('cuti-validasi-cuti') }}";
</script>
<script src="{{ asset("assets/js/hrd/cuti.js") }}"></script>
@endpush

