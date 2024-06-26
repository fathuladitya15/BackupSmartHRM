@extends('vBase')
@section('title') {{ 'List Laporan Produksi' }}@endsection

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
                <h5 class="mb-0">List Produk </h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Laporan Baru
                </button>
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

{{-- modal tambah data --}}
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="modalTambahLongTitle">Tambah Laporan Produksi</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_laporan" method="POST" action="{{ route('laporan-produksi-add') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="from_date">Mulai dari</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="from_date" type="date" value=""  >
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="to_date">Sampai Dengan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="to_date" type="date" value=""  >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="keterangan">Keterangan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="keterangan" type="text" value=""  >
                        </div>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal edit data --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="modalEditLongTitle">Edit Laporan Produksi</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update_laporan" method="POST" action="{{ route('laporan-produksi-update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="from_date">Mulai dari</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="from_date" type="date" value="" id="from_date" >
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="to_date">Sampai Dengan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="to_date" type="date" value="" id="to_date" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="keterangan">Keterangan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="keterangan" type="text" value="" id="keterangan" >
                        </div>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
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


    var url_get = "{{ route('laporan-produksi-get') }}";
    function edit(id) {
        const button = document.getElementById('edit_'+id);
        const stoploading   = '<i class="bx bx-edit-alt"></i>Edit';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';


        $.ajax({
            url : url_get,
            type: 'POST',
            data: {id:id},
            beforeSend: function() {
                button.innerHTML = loading;
                button.disabled = true;
            },success : function(s) {
                console.log(s);
                $('#modalEdit').modal('show');
                $('#id').val(s.id);
                $('#from_date').val(s.from_date);
                $('#to_date').val(s.to_date);
                $('#keterangan').val(s.keterangan);
            }, error : function(e) {
                console.log(e);
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi tim IT",
                    icon: "error"
                });
            },complete: function() {
                button.innerHTML  = stoploading;
                button.disabled = false;

            }
        })
    }


    $("#update_laporan").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : $(this).serialize(),
            type : "POST",
            beforeSend :  function() {
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }, success : function(s) {
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
                $("#modalEdit").modal('hide');
                $("#update_laporan").trigger('reset');
            }, error : function(e) {
                console.log(e);
                var errors = '';
                $.each(e.responseJSON.errors, function(key, value) {
                    errors += value + '<br>'; // Membuat daftar pesan kesalahan
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errors
                });
            }, complete: function() {

                table.DataTable().ajax.reload();
                // Swal.close();

            }
        })
    })

    $("#add_laporan").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : $(this).serialize(),
            type : "POST",
            beforeSend :  function() {
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }, success : function(s) {
                if(s.status == true) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });
                    $("#modalTambah").modal('hide');
                    $("#add_laporan").trigger('reset');
                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "info"
                    });
                }
            }, error : function(e) {
                console.log(e);
                var errors = '';
                $.each(e.responseJSON.errors, function(key, value) {
                    errors += value + '<br>'; // Membuat daftar pesan kesalahan
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errors
                });
            }, complete: function() {

                table.DataTable().ajax.reload();
                // Swal.close();

            }
        })
    })


    var url_delete = "{{ route('laporan-produksi-delete') }}";
    function hapus(id) {
        const button = document.getElementById('hapus_'+id);
        const stoploading   = '<i class="bx bx-trash"></i>Hapus';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';

        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menghapus data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus Produk!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                        url : url_delete,
                        type: 'DELETE',
                        data: {id:id},
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Loading...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                        },success : function(s) {
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
                            console.log(e);
                            Swal.fire({
                                title: "Terjadi kesalahan",
                                text: "Hubungi tim IT",
                                icon: "error"
                            });
                        },complete: function() {
                            button.innerHTML  = stoploading;
                            button.disabled = false;
                            table.DataTable().ajax.reload();


                        }
                    })
            }
        });

    }

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


</script>

@endpush
