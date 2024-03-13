@extends('vBase')
@section('title') {{ 'Referensi Kerja' }} @endsection
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}">

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
                              <th>No.Surat</th>
                              <th>ID Karyawan</th>
                              <th>Nama Karyawan</th>
                              <th>Awal Masuk</th>
                              <th>Akhir Masuk</th>
                              <th>Tanggal Pembuatan</th>
                              <th>Keterangan</th>
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
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Referensi Kerja</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="" id="id_rf">
                <input type="hidden" name="status" value="2" id="status">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12" style="text-align:center;">
                            <label class="col-form-label" for="basic-default-name">Surat Referensi Kerja</label>
                            <table class="table"  width="100%">
                                <tr>
                                    <td width="220" style="text-align: right; padding-right: 10px;">Nomor : <span class="text-danger pl-1">*</span></td>

                                    <td style="text-align: left; padding-left: 0px;"><input type="text" name="no_surat" placeholder="Isi Nomor Surat" required="required" ></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <p>
                            Yang bertandatangan di bawah ini Direktur Alih Daya PT. Proven Force Indonesia, menerangkan bahwa:
                        </p>
                    </div>
                    <div class="row" style="text-items: center; padding-left: 40px;">
                        <table class=" " width="50" style="align-content: center" >
                            <tr>
                                <td>Nama</td>
                                <td width="10">:</td>
                                <td id="nama_karyawan"></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td width="10">:</td>
                                <td id="nik"></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td width="10">:</td>
                                <td id="alamat"></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td width="10">:</td>
                                <td id="jabatan"></td>
                            </tr>
                        </table>
                    </div>
                    <br><br>
                    <div class="row" id="text">
                        <p id="text">
                        </p>

                    </div>
                    <br>
                    <div class="row">
                        <div class="tanggal_pembuatan" id="tanggal_pembuatan">
                        </div>
                        <div class="">
                            PT. Proven Force Indonesia
                        </div>
                    </div>
                    <div class="row">
                        <img class="gambar_tanda_tangan_kr" src="" alt="" id="gambar_tanda_tangan_kr" width="100"> <br  />
                        <p  id="nama_ttd_karyawan"><b><u>M. Nur Rachmat Gani</u></b> <br>
                            Direktur Alih Daya
                        </p>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Kirim Ke Direktur Alih Daya</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data            = "{{ route('data-rf') }}";
    var url_save            = "{{ route('update-rf') }}";
    var url_update          = "{{ route('update-rf') }}";
    var url_detail          = "{{ route('detail-rf') }}";
    var url_get_karyawan    = "{{ route('peringatan-data-karyawan') }}";

</script>
<script src="{{ asset('assets/js/supervisor/referensi_kerja.js') }}"></script>

<script>
function acc(id) {
    const edit          = document.getElementById("acc_"+id+"");
    const stoploading   = '<i class="bx bx-check"></i>Setujui';
    const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_update,
        data: {id:id,status : 1},
        type: "POST",
        beforeSend: function() {
            edit.innerHTML = loading;
            edit.disabled = true;
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
                    icon: "warning"
                });
            }
        },error: function(e) {
            Swal.fire({
                title: "Terjadi kesalahan",
                text: "Hubungi tim IT",
                icon: "error"
            });
        },complete: function() {
            edit.innerHTML = stoploading;
            edit.disabled = false;
            table.DataTable().ajax.reload();

        }
    })
}

function send(id) {
    const edit          = document.getElementById("send_"+id+"");
    const stoploading   = '<i class="bx bxs-send"></i>Kirim Ke Karyawan';
    const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_update,
        data: {id:id,status : 5},
        type: "POST",
        beforeSend: function() {
            edit.innerHTML = loading;
            edit.disabled = true;
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
                    icon: "warning"
                });
            }
        },error: function(e) {
            Swal.fire({
                title: "Terjadi kesalahan",
                text: "Hubungi tim IT",
                icon: "error"
            });
        },complete: function() {
            edit.innerHTML = stoploading;
            edit.disabled = false;
            table.DataTable().ajax.reload();

        }
    })
}

function detail(id) {
    const edit          = document.getElementById("detail_"+id+"");
    const stoploading   = '<i class="bx bx-edit-alt"></i>Detail';
    const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_detail,
        data: {id:id},
        type: "POST",
        beforeSend: function() {
            edit.innerHTML = loading;
            edit.disabled = true;
        }, success: function(s) {
            console.log(s);
            if(s.status == true) {
                $("#exampleModalCenter").modal("show");
                document.getElementById("nama_karyawan").innerHTML = s.nama_karyawan;
                document.getElementById("nik").innerHTML = s.nik;
                document.getElementById("alamat").innerHTML = s.alamat;
                document.getElementById("jabatan").innerHTML = s.jabatan;
                $("#id_rf").val(s.id);
            }else {
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi tim IT",
                    icon: "error"
                });
                console.log(s)
            }
        }, error : function(e) {
            console.log(e)
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
</script>

@endpush
