@extends('vBase')
@section('title') {{ 'Data Izin Karyawan' }}@endsection
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Izin  /</span>Data Pengajuan Izin</h4>
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
                <h5 class="mb-0">Data Izin </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>No Surat</th>
                                <th>ID Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Alasan</th>
                                <th>Jam Keluar</th>
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
<div class="modal fade" id="modalView" role="dialog"  >
    <div class="modal-dialog  modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="" style="color: black">File Izin </h5>
            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form >
                    <div class="col-lg-12" style="text-align: center;">
                        <img src="" alt="" id="image_file_izin" style="width: 50%">
                    </div>
                    <div class="col-lg-12">
                        <iframe src="" width="100%" height="500px" id="pdf_file_izin" frameborder="0"></iframe>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('data-izin-spv') }}";
    var url_save    = "{{ route('izin-save') }}";
    var url_detail  = "{{ route('izin-detail') }}";
    var assets      = "{{ asset('') }}";
    var url_files   = "{{ route('izin-file') }}";
    var url_acc     = "{{ route('izin-acc') }}";
</script>
<script src="{{ asset('assets/js/supervisor/izin_default.js') }}"></script>
<script>
    function view(id) {
        const detail  = document.getElementById("view_"+id+"");
        const stoploading = '<i class="menu-icon tf-icons bx bx-folder-open"></i> Lihat File';
        const loading = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
        $.ajax({
            url : url_files,
            type :  "POST",
            data : {id : id },
            beforeSend : function () {
                detail.innerHTML = loading;
                detail.disabled  = true;
            },success : function(s) {
                if(s.status == true) {
                    if(s.type_file == 'pdf') {
                        document.getElementById("pdf_file_izin").style.display = "block";
                        document.getElementById("pdf_file_izin").src =  s.links ;
                    }else {
                        document.getElementById("pdf_file_izin").style.display = "none";
                        document.getElementById("image_file_izin").src =  s.links ;
                    }
                    $("#modalView").modal("show");
                    detail.innerHTML = stoploading;
                    detail.disabled = false;
                }
            }, error : function(e) {
                Swal.fire({
                    title: "Terjadi Kesalahan !",
                    text: "Hubungi Tim IT !",
                    icon: "error"
                });
            }, complete :  function() {
                detail.innerHTML = stoploading;
                detail.disabled = false;
            }
        })
    }

    $('#modalView').on('hidden.bs.modal', function () {
        document.getElementById("pdf_file_izin").style.display = "none";
        document.getElementById("pdf_file_izin").src = "";
        document.getElementById("image_file_izin").src = "";
    });

    function approved(id) {
        $.ajax({
            url : url_acc,
            data: {id:id},
            type : 'POST',
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
            },success: function(s) {
                console.log(s);
                if(s.status == true) {
                    Swal.fire(s.title, s.pesan, "success");
                }else {
                    Swal.fire('Terjadi Kesalahan',"", "error");
                }
            },error : function(e) {
                console.log(e);
                Swal.fire('Terjadi Kesalahan',"", "error");

            },complete: function() {
                table.DataTable().ajax.reload();
            }
        })
    }
</script>
@endpush

