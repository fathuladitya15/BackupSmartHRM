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
    var table = $("#myTable").dataTable();
</script>
@endpush
