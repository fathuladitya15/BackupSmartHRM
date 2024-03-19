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
                            <th>Keterangan</th>
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
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Buat penguman</h5>
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
                            <textarea name="keterangan" id="keterangan" class="form-control" required cols="30" rows="10"></textarea>
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
@endsection
@push('js')
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
            data: 'keterangan',
            name: 'keterangan',
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
        var formData = new FormData(this);

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
</script>

@endpush
