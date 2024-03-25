@extends('vBase')
@section('title'){{ 'Laporan Produksi' }} @endsection

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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Produk /</span>Laporan Produksi</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">List Laporan Produksi </h5>
            </div>
            <div class="card-title">
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                              <th>No</th>
                              <th>Keterangan</th>
                              <th>Periode</th>
                              <th>Grand Total</th>
                              <th>Total Tagihan + Fee</th>
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
    var url_data = "{{ route('laporan-produksi-data') }}";
    var table = $('#myTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: url_data,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },  {
            data: 'keterangan',
            name: 'keterangan',
        },{
            data: 'periode',
            name: 'periode',
        },
        {
            data: 'total_produk',
            name: 'total_produk',
            orderable: false,
            searchable: false
        },
        {
            data: 'total_tagihan',
            name: 'total_tagihan',
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

    var url_data_laporan = "{{ route('laporan-produksi-isi') }}";
    function data(id) {
        const button = document.getElementById('data_'+id);
        const stoploading   = '<i class="menu-icon tf-icons bx bx-table"></i>Data';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';

        $.ajax({
            url : url_data_laporan,
            type:"GET",
            data: {id:id},
            beforeSend: function() {
                button.innerHTML = loading;
                button.disabled = true;
            },success: function(s) {
                console.log(s);
                if(s.link != null ) {
                    Swal.fire({
                        title: 'Mengalihkan',
                        html: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    // Setelah beberapa waktu (dalam milidetik), arahkan pengguna ke halaman yang diinginkan
                    setTimeout(function() {
                        window.location.href = s.link; // Ganti dengan halaman yang diinginkan
                    }, 2000);
                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "warning"
                    });
                }
            },error : function(e) {
                console.log(e);
            },complete: function(){
                button.innerHTML = stoploading;
                button.disabled = false;
            }
        })
    }

    var url_acc = "{{ route('laporan-produksi-update') }}";
    function acc(id) {
        const button        = document.getElementById('acc_'+id);
        const stoploading   = '<i class="menu-icon tf-icons bx bx-check-circle"></i>';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';

        $.ajax({
            url : url_acc,
            data: {id:id,status : 2},
            type: "POST",
            beforeSend: function() {
                button.innerHTML = loading;
                button.disabled = true;
            },success: function(s) {
                console.log(s);
                if(s.status == true) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });
                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "warning"
                    });
                }
            }, error : function(e) {
                    Swal.fire({
                        title: 'Terjadi Kesalahan',
                        text: 'Hubungi Tim IT',
                        icon: "error"
                    });
            }, complete: function() {
                // table.dataTable().ajax.reload();
                table.DataTable().ajax.reload();

            }
        })
    }
</script>

@endpush
