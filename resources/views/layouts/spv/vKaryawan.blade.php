@extends('vBase')
@section('title')
{{ 'Data Karyawan' }}
@endsection

@push('css')
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
        .circular_image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            background-color: blue;
            /* commented for demo
            float: left;
            margin-left: 125px;
            margin-top: 20px;
            */

            /*for demo*/
            display:inline-block;
            vertical-align:middle;
        }
        .circular_image img{
            width:100%;
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan /</span>Kelola Karyawan </h4>
<br>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Karyawan</h5>
            </div>
            <div class="card-title" style="float: right;">
                <a href="{{ route('karyawan-add') }}" class="btn btn-success" style="float: right;">
                    <i class='bx bx-check'></i>Setujui Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>face id</th>
                            <th>id karyawan</th>
                            <th>photo</th>
                            <th>Nama</th>
                            <th>divisi</th>
                            <th>Jabatan</th>
                            <th>Gol Karyawan</th>
                            <th>tgl Bergabung</th>
                            <th>Selesai Kontrak</th>
                            <th>disetujui oleh</th>
                            <th>disetujui pada</th>
                            <th>disetujui </th>
                            <th>status akun</th>
                            <th>Setujui</th>
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
<script>
    var url_data  = "{{ route('data-kr-spv') }}";
    var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [
        {
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data:'face_id',
        name:'face_id',
        orderable: false,
        searchable: false
    },
    {
        data:'id_karyawan',
        name:'id_karyawan',
        orderable: false,
        searchable: false
    },
    {
        data:'photo',
        name:'photo',
        orderable: false,
        searchable: false
    },
    {
        data: 'name',
        name: 'name',
        orderable: false,
        searchable: false
    },
    {
        data: 'nama_divisi',
        name: 'nama_divisi',
        orderable: false,
        searchable: false
    },
    {
        data: 'nama_jabatan',
        name: 'nama_jabatan',
        orderable: false,
        searchable: false
    },
    {
        data: 'gol_karyawan',
        name: 'gol_karyawan',
        orderable: false,
        searchable: false
    },
    {
        data: 'join_date',
        name: 'join_date',
        orderable: false,
        searchable: false
    },
    {
        data: 'end_date',
        name: 'end_date',
        orderable: false,
        searchable: false
    },
    {
        data: 'acc_on',
        name: 'acc_on',
        orderable: false,
        searchable: false
    },
    {
        data: 'acc_by',
        name: 'acc_by',
        orderable: false,
        searchable: false
    },
    {
        data: 'acc',
        name: 'acc',
        orderable: false,
        searchable: false
    },
    {
        data: 'status_karyawan',
        name: 'status_karyawan',
        orderable: false,
        searchable: false
    },

    {
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    }, ]
}) ;

</script>
@endpush
