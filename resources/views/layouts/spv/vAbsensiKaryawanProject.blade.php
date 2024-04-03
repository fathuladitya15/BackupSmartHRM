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
                    <table class="table table-striped" id="myTable"   >
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
                                <th>Catatan</th>
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
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Filter Data</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data" method="GET" action="{{ route('absensi-search-one',['karyawan' => Request::segment(2),'id' => $kr]) }}">
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

@push('js')
    <script>
       var url_data = "{{ $route_data_table }}";
    </script>
    <script>
        var table  = $('#myTable').dataTable({
            processing: true,
            serverSide: true,
            ajax: url_data,
            columns: [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'id_karyawan',
                name: 'id_karyawan',
                orderable: false,
                searchable: false
            },
            {
                data: 'nama_karyawan',
                name: 'nama_karyawan',
                orderable: false,
                searchable: false
            },{
                data: 'jabatan',
                name: 'jabatan',
                orderable: false,
                searchable: false
            },{
                data: 'divisi',
                name: 'divisi',
                orderable: false,
                searchable: false
            },{
                data: 'tanggal',
                name: 'tanggal',
                orderable: false,
                searchable: false
            },{
                data: 'jam_masuk',
                name: 'jam_masuk',
                orderable: false,
                searchable: false
            },{
                data: 'mulai_istirahat',
                name: 'mulai_istirahat',
                orderable: false,
                searchable: false
            },{
                data: 'selesai_istirahat',
                name: 'selesai_istirahat',
                orderable: false,
                searchable: false
            },{
                data: 'jam_keluar',
                name: 'jam_keluar',
                orderable: false,
                searchable: false
            },{
                data: 'shift',
                name: 'shift',
                orderable: false,
                searchable: false
            },{
                data: 'lokasi_absen_plg',
                name: 'lokasi_absen_plg',
                orderable: false,
                searchable: false
            },  {
                data: 'catatan',
                name: 'catatan',
                orderable: false,
                searchable: false
            }]
        });
    </script>
@endpush

