@extends('vBase')
@section('title') {{ 'List Produk' }} @endsection

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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Produk /</span>List Produk</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">List Produk </h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalUpload" >
                    <i class='bx bx-upload'></i> Upload Data Produk
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Data Produk
                </button>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                              <th>#</th>
                              <th>Nomor Produk</th>
                              <th>Nama Produk</th>
                              <th>Harga Produk</th>
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

{{-- modal upload file excel --}}
<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUploadTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Upload Data Produk</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="upload_produk" method="POST" action="{{ route('list-produk-upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="files">File Excel</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="files" type="file" value="" id="files" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="tipe_produk">Pilih Ketegori Produk</label>
                            <select name="tipe_produk" id="tipe_produk" class="form-control">
                                <option value="">-- Pilih Kategori Produk --</option>
                                <option value="primary">Primary</option>
                                <option value="n_primary">Non Primary</option>
                            </select>
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

{{-- modal edit produk --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Edit Data Produk</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit_produk" method="POST" action="{{ route('list-produk-update') }}" >
                @csrf
                <input type="hidden" name="id" value="" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="nama_produk">Nama Produk</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="nama_produk" type="text" value="" id="nama_produk" >
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="nomor_produk">Nomor Produk</label>
                            <input class="form-control"   name="nomor_produk" type="text" value="" id="nomor_produk" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="harga_produk">Masukan Harga Produk</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="harga_produk" type="text" value="" id="harga_produk" required>

                        </div>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambah" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Data Produk</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_produk" method="POST" action="{{ route('list-produk-add') }}" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="nama_produk">Nama Produk</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="nama_produk" type="text" value="" id="nama_produk" >
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="nomor_produk">Nomor Produk</label>
                            <input class="form-control"   name="nomor_produk" type="text" value="" id="nomor_produk" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="harga_produk">Masukan Harga Produk</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="harga_produk" type="text" value="" id="harga_produk" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="tipe_produk">Pilih Ketegori Produk</label>
                            <select name="tipe_produk" id="tipe_produk" class="form-control">
                                <option value="">-- Pilih Kategori Produk --</option>
                                <option value="primary">Primary</option>
                                <option value="n_primary">Non Primary</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="satuan_produk">Satuan Produk</label>
                            <select name="satuan_produk" id="satuan_produk" required class="form-control">
                                <option value="">-- Pilih Satuan Produk --</option>
                                <option value="pcs">Pieces (pcs)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
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
    var url_data = "{{ route('list-produk-data',['id_client' => Auth::user()->id_client]) }}";
    var table = $('#myTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: url_data,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },  {
            data: 'no_produk',
            name: 'no_produk',
        },{
            data: 'nama_produk',
            name: 'nama_produk',
        },
        {
            data: 'harga_produk',
            name: 'harga_produk',
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
    $('#upload_produk').submit(function (e) {
        e.preventDefault()
        var formData = new FormData(this);
        $.ajax({
            url : $(this).attr('action'),
            data:formData,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                Swal.fire({
                    title: "Mohon Tunggu ... !",
                    html: "Jangan Tinggalkan Halaman ini <b></b> ",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            },
            success: function(s) {
                if(s.status == true) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });
                    $("#modalUpload").modal("hide");
                    $("#upload_produk").trigger("reset");

                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "warning"
                    });
                }
            }, error : function(e) {

                console.log(e);
            },beforeSend: function() {
                Swal.close();
                table.DataTable().ajax.reload();
            }
        })
    });

    var url_get = "{{ route('list-produk-get') }}";
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
                $('#nama_produk').val(s.nama_produk);
                $('#nomor_produk').val(s.no_produk);
                $('#harga_produk').val(s.harga_produk);
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


    $("#edit_produk").submit(function (e) {
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
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                });
                $("#modalEdit").modal('hide');
                $("#edit_produk").trigger('reset');
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

    $("#add_produk").submit(function (e) {
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
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                });
                $("#modalTambah").modal('hide');
                $("#add_produk").trigger('reset');
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


    var url_delete = "{{ route('list-produk-delete') }}";
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
                            Swal.fire({
                                title: s.title,
                                text: s.pesan,
                                icon: "success"
                            });
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

</script>

@endpush
