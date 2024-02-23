@extends('vBase')
@section('title') {{ 'Lembur - Supervisor' }}@endsection
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

</style>

@endpush

@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lembur /</span>Data Lembur</h4>


<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Lembur</h5>
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
                            <th>Tanggal</th>
                            <th>Tugas dikerjakan</th>
                            <th>mulai jam</th>
                            <th>sampai jam</th>
                            <th>Jumlag jam</th>
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


@endsection

@push('js')

<script>
    var url_data    = "{{ route('lembur-spv-internal',['hash' => HashVariable(Auth::user()->id_karyawan)]) }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var url_save    = "{{ route('lembur-save') }}";
</script>
<script src="{{ asset('assets/js/supervisor/lembur.js') }}"></script>

@endpush
