@extends('vBase')
@section('title') {{  'Data Kategori' }}@endsection
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

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Cuti /</span>Kategori cuti</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Kategori Cuti</h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Kategori
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Nama Kategori</th>
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
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Kategori Cuti</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                <input type="hidden" name="id_cuti" id="id_cuti" value="">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class=" col-form-label" for="basic-default-name">Nama Kategori</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="nama_kategori" id="nama_kategori" type="text" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    var url_data = "{{ route('cuti-kategori-data') }}";
    var url_save = "{{ route('cuti-kategori-save') }}";
    var url_detail = "{{ route('cuti-kategori-get') }}";
    var url_delete = "{{ route('cuti-kategori-delete') }}";
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
            data: 'nama_kategori',
            name: 'nama_kategori',
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

    $('#exampleModalCenter').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
        $("#id_cuti").val(null)
        document.getElementById("exampleModalLongTitle").innerHTML  = "Tambah Kategori Cuti";
    });

    $("#Add").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : url_save,
            data: new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function() {
                Swal.fire({
                    title: "Mohon Tunggu ... !",
                    html: "Jangan Tinggalkan Halaman ini <b></b> ",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            },success : function(s) {
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
                        icon: "error"
                    });
                }
            }, error : function(e) {
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi tim IT",
                    icon: "error"
                });
            },complete: function() {
                table.DataTable().ajax.reload();
                $("#exampleModalCenter").modal('hide');
            }
        })
    })

    function detail(id) {
        const edit          = document.getElementById("edit_"+id+"");
        const stoploading   = '<i class="bx bx-edit-alt"></i>Edit';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
        $.ajax({
            url : url_detail,
            data: {id:id},
            type: "POST",
            beforeSend: function() {
                edit.innerHTML = loading;
                edit.disabled = true;
            }, success: function(s) {
                var data = s.data;
                if(s.status == true) {
                    $("#exampleModalCenter").modal("show");
                    $("#nama_kategori").val(data.nama_kategori);
                    $("#id_cuti").val(data.id);
                    document.getElementById("exampleModalLongTitle").innerHTML  = "Update Data Cuti";

                }else {
                    Swal.fire({
                        title: "Terjadi kesalahan",
                        text: "Hubungi tim IT",
                        icon: "error"
                    });
                    // console.log(s)
                }
            }, error : function(e) {
                // console.log(e)
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi tim IT",
                    icon: "error"
                });
            },complete : function() {
                edit.innerHTML = stoploading;
                edit.disabled = false;
            }
        })
    }

    function hapus(id) {
        Swal.fire({
                title: "Anda yakin ingin mengahapus kategori ini ?",
                text: "Anda akan kehilangan data dan kemungkinan akan terjadi  error apabila kategori sudah digunakan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Kembali",
                confirmButtonText: "Hapus Kategori !"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : url_delete,
                        data: {id:id},
                        type: "DELETE",
                        beforeSend: function() {
                            Swal.fire({
                                title: "Sedang menghapus data !",
                                html: "Jangan Tinggalkan Halaman ini <b></b> ",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                        },success : function(s) {
                            if(s.status == true) {
                                Swal.fire({
                                    title : s.title,
                                    text : s.pesan,
                                    icon : "success"
                                });
                            }else {
                                Swal.fire({
                                    title: "Terjadi Kesalahan!",
                                    text : "Hubungi Tim IT Terkait !" ,
                                    icon : "error"
                                });
                            }
                        },error : function(e) {
                            Swal.fire({
                                title: "Terjadi Kesalahan!",
                                text : "Hubungi Tim IT Terkait !" ,
                                icon : "error"
                            });
                        },complete: function () {
                            table.DataTable().ajax.reload();
                        }
                    })
                }
        });
    }

</script>
@endpush
