@extends('vBase')
@section('title') {{  'Data Absensi ' }}@endsection
@push('css')


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
</style>
@endpush
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Absensi /</span> Data Absensi  {{ Str::title(Request::segment(2)) }} </h4>

<br><br>
@if ($errors->any())
@foreach ($errors->all() as $item)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $item }}
        <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endforeach
@endif
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data  Absensi {{ Str::title(Request::segment(2)) }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modalFilter"> <i class='bx bx-filter' ></i> Filter</button>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#modalDownload" style="float: right"> <i class='bx bx-download' ></i> Download</button>
                    </div>

                </div>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="table_Absensi"   >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Divisi</th>
                                <th>Tanggal</th>
                                <th>Absen Masuk</th>
                                <th>Mulai Istirahat</th>
                                <th>Selesai Istirahat</th>
                                <th>Absen Pulang</th>
                                <th>Shift</th>
                                <th>Lokasi Absen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>1</td>
                                    <td>{{ $item->id_karyawan }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->jabatan }}</td>
                                    <td>{{ $item->divisi }}</td>
                                    <td>{{ $item->tanggal_absensi }}</td>
                                    <td>{{ $item->waktu_masuk }}</td>
                                    <td>{{ $item->mulai_istirahat }}</td>
                                    <td>{{ $item->selesai_istirahat }}</td>
                                    <td>{{ $item->waktu_pulang }}</td>
                                    <td>{{ $item->ke }}</td>
                                    <td></td>
                                    <td>


                                    </td>
                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Filter Data</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data" method="GET" action="{{ route('absensi-search-one',['id' => $kr]) }}">
                @csrf
                <input type="hidden" value="{{ $kr }}" name="kr">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="" class="col-form-label">Pilih Karyawan</label>
                            <select name="id_karyawan" id="id_karyawan" class="form-control" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($karyawan as $kr )
                                    <option value="{{ $kr->id_karyawan }}">{{ $kr->id_karyawan }} - {{  $kr->name }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="" class="col-form-label">Dari</label>
                            <input type="date" class="form-control" name="from_date" value="" required>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="" class="col-form-label">Sampai</label>
                            <input type="date" class="form-control" name="to_date" value="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDownload" tabindex="-1" role="dialog" aria-labelledby="modalDownloadTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Download Data</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="" class="col-form-label">Dari</label>
                            <input type="date" class="form-control" name="from_date" value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="" class="col-form-label">Sampai</label>
                            <input type="date" class="form-control" name="to_date" value="">
                        </div>

                    </div>
                </div>
                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Download</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- @push('js')
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
@endpush --}}

