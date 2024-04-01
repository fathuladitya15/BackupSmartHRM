@extends('vBase')
@section('title') {{  'Permintaan Pembelian' }} @endsection


@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}">


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
    p#nama {
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Permintaan Pembelian /</span>{{ Request::segment(2) }}</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Permintaan Pembelian </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>No Pre Order</th>
                                <th>Divisi</th>
                                <th>Dibuat Oleh</th>
                                <th>Jumlah</th>
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

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('pre-order-ga-data') }}";
    var id          = "{{ $id_client }}";
    var table  = $('#myTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : url_data,
            type: "POST",
            data: {id_client : id},
        },
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },{
            data: 'no_po',
            name: 'no_po',
            orderable: false,
            searchable: false
        },{
            data: 'divisi',
            name: 'divisi',
            orderable: false,
            searchable: false
        }, {
            data: 'dibuat_oleh',
            name: 'dibuat_oleh',
            orderable: false,
            searchable: false
        }, {
            data: 'jumlah',
            name: 'jumlah',
            orderable: false,
            searchable: false
        },{
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false
        },{
            data: 'aksi',
            name: 'aksi',
            orderable: false,
            searchable: false
        }, ]
    }) ;

</script>
@endpush
