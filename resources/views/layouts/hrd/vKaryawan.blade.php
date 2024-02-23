@extends('vBase')
@section('title') {{  'Karyawan' }} @endsection


@section('content')
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan /</span>Kelola Karyawan </h4>

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
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Karyawan</h5>
            </div>
            <div class="card-title">

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Karyawan
                  </button>
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
                            <th>Kategori</th>
                            <th>Gol Karyawan</th>
                            <th>tgl Bergabung</th>
                            <th>Selesai Kontrak</th>
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

@push('js')

<script>
    var url_data = "{{ $link_data }}";
</script>
<script src="{{ asset('assets/js/hrd/data_karyawan.js') }}"></script>
@endpush
@endsection
