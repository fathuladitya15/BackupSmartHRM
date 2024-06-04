@extends('vBase')
@section('title') {{ 'Request Absensi' }}@endsection
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

    .select2-container {
            width: 100% !important; /* Menyesuaikan dengan lebar 100% dari parent element */
        }


        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px); /* Sesuaikan dengan tinggi input Bootstrap */
            line-height: 1.5; /* Sesuaikan dengan tinggi input Bootstrap */
            padding: .375rem .75rem; /* Sesuaikan dengan padding input Bootstrap */
            border: 1px solid #ced4da; /* Sesuaikan dengan border input Bootstrap */
            border-radius: .25rem; /* Sesuaikan dengan border-radius input Bootstrap */
        }
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Filter Data </h5>
            </div>
            <div class="card-body">
                <form id="form-search">
                    <div class="row">
                        <div class="col">
                            <label for="select-nama"> <b>Nama Karyawan</b></label>
                            <select name="nama_karyawan" id="select-nama" class="form-control"></select>
                        </div>
                        <div class="col">
                            <label for="from-date"><b>Mulai dari tanggal</b></label>
                            <input type="date" name="from-date" id="from_date" class="form-control"  aria-label="from-date">
                        </div>
                        <div class="col">
                            <label for="to-date"><b>Sampai dengan tanggal</b></label>
                            <input type="date" name="to_date" id="to_date" class="form-control" aria-label="to-date">
                          </div>
                          <div class="col">
                            <label for=""></label>
                            <button id="search" type="button" class="form-control btn btn-primary"><i class="bx bx-search-alt"></i> Cari</button>
                          </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Request Absensi {{ $dateNow }} </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Karyawan</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Nama Karyawan</th>
                                <th>Absen Masuk</th>
                                <th>Lokasi Absen</th>
                                <th>Detail Lokasi Absen</th>
                                <th>Shift</th>
                                <th>Catatan</th>
                                <th>Status</th>
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
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var url_data = "{{ route('data.absensi.request') }}";

    $(document).ready(function() {
        var table  = $("#myTable").dataTable({
            processing : true,
            serverSide: true,
            ajax: {
                url:url_data ,
                data: function (d) {
                    d.nama_karyawan = $('#select-nama').val();
                    d.from_date     = $('#from_date').val();
                    d.to_date       = $('#to_date').val();
                }
            },
            columns : [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },{
                data: 'id_karyawan',
                name: 'id_karyawan',
            },{
                data: 'divisi',
                name: 'divisi',
            },{
                data: 'jabatan',
                name: 'jabatan',
            },{
                data: 'nama_karyawan',
                name: 'nama_karyawan',
            },
            {
                data: 'jam',
                name: 'jam',
            },{
                data: 'lokasi_absen',
                name: 'lokasi_absen',
            },{
                data: 'detail_lokasi_absen',
                name : 'detail_lokasi_absen',
            },{
                data: 'shift',
                name: 'shift',
            },{
                data: 'catatan',
                name: 'catatan',
            },{
                data: 'status',
                name: 'status'
            }]
        })

        var url_select = "{{ route('data.absensi.request.karyawan') }}";
        $('#select-nama').select2({
            // dropdownParent: $("#modal-default"),
            ajax: {
                url: url_select,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // term pencarian dari pengguna
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (kr) {
                            return {
                                id: kr.id_karyawan,
                                text: kr.name // tampilkan nama di dropdown
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Cari Karyawan',
            minimumInputLength: 0 // minimal karakter yang harus dimasukkan sebelum pencarian dilakukan
        });

        $('#search').on('click', function() {
            table.DataTable().ajax.reload();
        });
    })
</script>
@endpush
