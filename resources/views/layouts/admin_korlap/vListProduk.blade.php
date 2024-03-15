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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
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
                              <th>Produk Kategori</th>
                              <th>Nama Produk</th>
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

@endsection

@push('js')

<script>
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

                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "warning"
                    });
                }
            }, error : function(e) {
                console.log(e);
            }
        })
    })
</script>

@endpush
