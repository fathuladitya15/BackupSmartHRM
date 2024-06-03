@extends('vBase')
@section('title') {{ 'Request Absensi' }}@endsection
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
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data  Absensi </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hari, Tanggal</th>
                                <th>Jam</th>
                                <th>Lokasi Request Absen</th>
                                <th>Detail Request Lokasi Absen</th>
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
<script>
    var url_data = "{{ route('data.absensi.request') }}";

    $(document).ready(function() {
        var table  = $("#myTable").dataTable({
            processing : true,
            serverSide: true,
            ajax: url_data,
            columns : [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },{
                data: 'tanggal',
                name: 'tanggal',
            },{
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
    })
</script>
@endpush
