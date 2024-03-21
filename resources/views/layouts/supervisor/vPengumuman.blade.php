@extends('vBase')
@section('title') {{ 'Pengumuman' }} @endsection

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

    .modal:nth-of-type(even) {
        z-index: 1052 !important;
    }
    .modal-backdrop.show:nth-of-type(even) {
        z-index: 1051 !important;
    }

</style>

@endpush
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengumuman /</span> Pengumuan</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Arsip Pengumuman </h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Buat Pengumuman
                </button>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                          <tr class="text-nowrap">
                            <th>No</th>
                            <th>Judul</th>
                            {{-- <th>Keterangan</th> --}}
                            <th>Dikirim Ke</th>
                            <th>Lampiran</th>
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
{{-- BUAT PENGUMUMAN --}}
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Buat pengumuman</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="id_karyawan">Dikirim ke</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="views" id="views" class="form-control">
                                <option value="1">Semua Karyawan</option>
                                <option value="2">Karyawan Project</option>
                                <option value="3">Karyawan Internal</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="judul">Judul</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Judul Pengumuman" name="judul" type="text" value="" id="judul">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="col-form-label">Keterangan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="hidden" name="keterangan" id="input_text">
                            <textarea  id="keterangan" class="form-control"  cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label">Lampiran</label>
                            <input type="file" name="lampiran[]" multiple class="form-control" >
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


{{-- EDIT PENGUMUMAN --}}
<div class="modal fade" id="edit" role="dialog"  tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" >
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Edit pengumuman</h5>
                <button type="button" class="btn btn-default close_modal_edit" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FormEdit" enctype="multipart/form-data" action="#">
                @csrf
                <input type="hidden" id="id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="id_karyawan">Dikirim ke</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="views_edit" id="views_edit" class="form-control">
                                <option value="1">Semua Karyawan</option>
                                <option value="2">Karyawan Project</option>
                                <option value="3">Karyawan Internal</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="judul">Judul</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Judul Pengumuman" name="judul_edit" type="text" value="" id="judul_edit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="col-form-label">Keterangan</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea  id="keterangan_edit" class="form-control"  cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label">Lampiran</label>
                            <div  id="tableLampiran">

                            </div>

                        </div>
                    </div>

                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close_modal_edit"  >Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- LAMPIRAN --}}
<div class="modal fade" id="modalView" role="dialog" tabindex="-1"   tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"  >
    <div class="modal-dialog modal-dialog-centered modal-lg"  role="document" >
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title black" id="exampleModalLongTitle">Edit pengumuman</h5> --}}
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="col-lg-12" style="text-align: center;">
                <img src="" alt="" id="image_file" style="width: 50%">
            </div>
            <div class="col-lg-12">
                <iframe src="" width="100%" height="500px" id="pdf_file" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal"> Tutup</button>
            </div>
        </div>
    </div>
</div>


@endsection


@push('js')
<script src="https://cdn.tiny.cloud/1/sm1yq4m3clzmzqnwsv0frgjbxnf3a7hy5ckbkyjmcr3oyf9c/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    var tinymceConfig = {
        selector: 'textarea',
        plugins: ['paste', 'link', 'table', 'lists'],
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link ',
        menubar: false,
        apiKey:'sm1yq4m3clzmzqnwsv0frgjbxnf3a7hy5ckbkyjmcr3oyf9c'
    }
    tinymce.init(Object.assign({}, tinymceConfig, { selector: '#keterangan' }));
    tinymce.init(Object.assign({}, tinymceConfig, { selector: '#keterangan_edit' }));


</script>
<script>
    var url_data = "{{ route('pengumuman-data') }}";
    var table = $("#myTable").dataTable({
        ajax : url_data,
        processing: true,
        serverSide: true,
        columns : [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },{
            data: 'judul',
            name: 'judul',
        },{
            data: 'views',
            name: 'views',
            orderable: false,
            searchable: false
        },{
            data: 'lampiran',
            name: 'lampiran',
            orderable: false,
            searchable: false
        },{
            data: 'aksi',
            name: 'aksi',
            orderable: false,
            searchable: false
        }]
    })

    $('#Add').submit(function(e){
        e.preventDefault();
        var content = tinymce.get('keterangan').getContent();
        var formData = new FormData(this);
        formData.append('keterangan' ,content)
        $.ajax({
            url: '{{ route("pengumuman-add") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend:function(){
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            },
            success: function(s){
                console.log(s);
                if(s.status == true){
                    Swal.fire({
                        icon: 'success',
                        title: s.title,
                        html: s.pesan
                    });
                    $("#exampleModalCenter").modal('hide');
                    $("#Add").trigger('reset');
                }else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan',
                        html: 'hubungi Tim IT !'
                    });
                }
            },
            error: function(e){
                var errors = '';
                $.each(e.responseJSON.errors, function(key, value) {
                    errors += value + '<br>'; // Membuat daftar pesan kesalahan
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errors
                });
            },complete : function() {
                table.DataTable().ajax.reload();
            }
        });
    });
    var url_delete = "{{ route('pengumuman-delete') }}";

    function hapus(id) {
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menghapus pengumuman ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus !"
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
                            table.DataTable().ajax.reload();


                        }
                    })
            }
        });


    }

    function detail(id) {
        const edit          = document.getElementById("detail_"+id+"");
        const stoploading   = '<i class="bx bx-edit-alt"></i>Detail';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
        var tableHTML       = '<table class="table table-bordered" border="1"><tr><th>filename</th><th>aksi</th></tr>';

        $.ajax({
            url : "{{ route('pengumuman-get') }}",
            type: "POST",
            data: {id:id},
            beforeSend: function() {
                edit.innerHTML  = loading;
                edit.disabled   = true;

            },success: function(s) {
                var data = s.data;
                var files = s.lampiran;
                $("#edit").modal('show');
                $("#id").val(data.id);
                $("#judul_edit").val(data.judul);
                var ti = tinymce.get('keterangan_edit');
                ti.setContent(data.keterangan);
                $("#views_edit").val(data.views)

                $.each(files, function(index, item) {
                    tableHTML += '<tr><td>' + item.filename + '</td><td> <a href="javascript:void(0)" onclick="lihat_files('+item.id+')" class="btn btn-sm btn-primary " id="vImage_'+item.id+'" > Lihat </a> <a href="javascript:void(0)" onclick="hapus_file('+item.id+')" class="btn btn-sm btn-danger " id="hapus_file_'+item.id+'" >Hapus File </a></td></tr>';
                });
                tableHTML += '</table>';
                $('#tableLampiran').html(tableHTML);
            },error : function(e) {
                var errors = '';
                $.each(e.responseJSON.errors, function(key, value) {
                    errors += value + '<br>'; // Membuat daftar pesan kesalahan
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errors
                });
            },complete :  function() {
                edit.innerHTML  = stoploading;
                edit.disabled   = false;
            }

        })
    }

    function lihat_files(id) {
        var assets           = "{{ asset('') }}";
        const view          = document.getElementById("vImage_"+id+"");
        const stoploading   = 'Lihat';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
        $.ajax({
            url : "{{ route('pengumuman-get-files') }}",
            type: "POST",
            data : {id : id},
            beforeSend: function() {
                view.innerHTML  = loading;
                view.disabled   = true;
            },success : function(s) {
                var tipe_file = s.extension;
                $("#modalView").modal("show");
                if(tipe_file == 'pdf') {
                    document.getElementById("image_file").style.display = "none";
                    document.getElementById("pdf_file").src =  s.path ;
                }else {
                    document.getElementById("pdf_file").style.display = "none";
                    document.getElementById("image_file").src =  s.path ;
                }
                // console.log(s);
            },error : function(e) {
                var errors = '';
                $.each(e.responseJSON.errors, function(key, value) {
                    errors += value + '<br>'; // Membuat daftar pesan kesalahan
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errors
                });
            }, complete: function(){
                view.innerHTML  = stoploading;
                view.disabled   = false;
            }
        })
    }

    $(".close_modal_edit").click(function () {
        $('#FormEdit').trigger('reset');
        $('#tableLampiran').empty();
        $("#edit").modal('hide')
    });


    var url_delete_file = "{{ route('pengumuman-delete-files') }}";
    function hapus_file(id) {
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menghapus file lampiran ini ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus File Lampiran!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                        url : url_delete_file,
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
                            $("#edit").modal('hide');


                        }
                    })
            }
        });


    }

    $("#FormEdit").submit(function(e) {
        e.preventDefault();
    })


</script>

@endpush
