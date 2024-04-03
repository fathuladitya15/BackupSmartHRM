@extends('vBase')
@section('title') {{ 'Kelola Lembur' }} @endsection
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lembur /</span>Data Lembur</h4>
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
        @if(session()->has('error'))
            <div class="alert alert-error alert-dismissible fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                            <th>Tanggal</th>
                            <th>Tugas dikerjakan</th>
                            <th>Mulai Jam</th>
                            <th>Sampai Jam</th>
                            <th>Jumlah Jam</th>
                            <th>Status</th>
                            <th>Disetujui Oleh</th>
                            <th></th>
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
    var url_data    = "{{ route('data-lembur-karyawan-data') }}";
    var url_save    = "{{ route('lembur-save') }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var url_update  = "{{ route('data-lembur-update') }}";
    var assets      = "{{ asset('') }}";
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
        },{
            data: 'tanggal_lembur',
            name: 'tanggal_lembur',
            orderable: false,
            searchable: false
        },
        {
            data: 'tugas',
            name: 'tugas',
            orderable: false,
            searchable: false
        },
        {
            data: 'jam_mulai',
            name: 'jam_mulai',
            orderable: false,
            searchable: false
        },
        {
            data: 'jam_selesai',
            name: 'jam_selesai',
            orderable: false,
            searchable: false
        },
        {
            data: 'total_jam',
            name: 'total_jam',
            orderable: false,
            searchable: false
        },
        {
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false
        },
        {
            data: 'disetujui_oleh',
            name: 'disetujui_oleh',
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

    function acc(id) {
        $.ajax({
            url : url_update,
            data: {id : id,status : 1},
            type : "POST",
            beforeSend: function() {
                Swal.fire({
                    title: "Mohon Tunggu ...!",
                    didOpen: () => {
                        Swal.showLoading();}
                    })
            },success : function(s) {
                if(s.status == true) {
                    Swal.fire(s.title,s.pesan, "success");
                }else {
                    Swal.fire('Error ! ','Hubungi tim IT', "error");

                }
            },error : function(e) {
                    Swal.fire('Error ! ','Hubungi tim IT', "error");
            },complete: function() {
                table.DataTable().ajax.reload();
            }
        })
    }
</script>

@endpush
