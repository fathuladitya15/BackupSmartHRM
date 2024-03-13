@extends('vBase')
@section('title') {{ 'Surat Referensi Kerja' }} @endsection

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
    p#nama_karyawan {
        margin-top: -50px !important;
    }
    p#nama_korlap {
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Surat /</span>Referensi Kerja</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Surat Referensi Kerja </h5>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>No Surat</th>
                                <th>Approved On</th>
                                <th>Approved By</th>
                                <th>status</th>
                                <th>actions</th>
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
    var url_data = "{{ route('data-rf') }}";
</script>
<script>
    var table = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },  {
        data: 'no_surat',
        name: 'no_surat',
        orderable: false,
        searchable: false
    },
     {
        data: 'acc_on',
        name: 'acc_on',
        orderable: false,
        searchable: false
    },{
        data: 'acc_surat',
        name: 'acc_surat',
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
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    },]
});
</script>

@endpush

