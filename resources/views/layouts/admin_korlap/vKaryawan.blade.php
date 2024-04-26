@extends('vBase')
@section('title') {{  'Karyawan' }} @endsection


@section('content')
@push('css')
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
        .circular_image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            background-color: blue;
            /* commented for demo
            float: left;
            margin-left: 125px;
            margin-top: 20px;
            */

            /*for demo*/
            display:inline-block;
            vertical-align:middle;
        }
        .circular_image img{
            width:100%;
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan /</span>Kelola Karyawan </h4>

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
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Karyawan</h5>
            </div>
            <div class="card-title" style="float: right;">
                <a href="{{ route('karyawan-add') }}" class="btn btn-primary" style="display: inline-block; width: auto;">
                    <i class='bx bx-plus'></i>Tambah
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalUpload" style="display: inline-block; width: auto;">
                    <i class='bx bx-upload'></i> Upload
                </button>
                <button type="button" id="quick_" data-bs-toggle="modal" data-bs-target="#modalUpdate" class="btn btn-primary" style="display: none;"><i class='bx bx-cog'></i> Quick Update</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>#</th>
                            <th>face id</th>
                            <th>id karyawan</th>
                            <th>photo</th>
                            <th>Nama</th>
                            <th>divisi</th>
                            <th>Jabatan</th>
                            <th>Gol Karyawan</th>
                            <th>tgl Bergabung</th>
                            <th>Selesai Kontrak</th>
                            <th>disetujui oleh</th>
                            <th>disetujui pada</th>
                            <th>disetujui </th>
                            <th>status akun</th>
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

<div class="modal fade" id="modalUpload" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="ImportKaryawan" class="modal-content" action="{{ route('karyawan-upload') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="modal-header">
                <h5 style="color: black" class="modal-title" id="modalUploadTitle">Upload Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBackdrop" class="form-label">Upload file Excel</label>
                        <input type="file" id="file" name="file_excel" class="form-control"  />

                        <span><a href="{{ route('karyawan-export') }}">Download Tamplate disini </a></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalUpdate" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="ImportKaryawan" class="modal-content" action="{{ route('karyawan-upload') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="modal-header">
                <h5 style="color: black" class="modal-title" id="">Quick Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBackdrop" class="form-label">Pilih Tipe Update</label>
                        <select name="tipe_update" id="tipe_update" class="form-control">
                            <option value="">-- Pilih Tipe Update --</option>
                            <option value="jabatan">Jabatan</option>
                            <option value="divisi">Divisi</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3" id="jabatan" style="display: none">
                        <label for="nameBackdrop" class="form-label">Pilih Jabatan</label>
                        <select name="tipe_update" id="" class="form-control">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="jabatan">Jabatan</option>
                            <option value="jabatan">Jabatan</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3" id="divisi" style="display: none">
                        <label for="nameBackdrop" class="form-label">Pilih divisi</label>
                        <select name="tipe_update" id="" class="form-control">
                            <option value="">-- Pilih divisi --</option>
                            <option value="divisi">divisi</option>
                            <option value="divisi">divisi</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

@push('js')

<script>
    var url_data = "{{ $link_data }}";
</script>
<script src="{{ asset('assets/js/admin_korlap/data_karyawan.js') }}"></script>
<script>
    document.getElementById("tipe_update").addEventListener("change", function() {
        var selectedValue = this.value;
        // document.getElementById("result").innerText = "Nilai yang dipilih: " + selectedValue;
        console.log("Nilai yang dipilih: " + selectedValue);
        if(selectedValue == 'jabatan' ) {
            document.getElementById(selectedValue).style.display = 'block';
            document.getElementById('divisi').style.display = 'none';
        }
        else if(selectedValue == 'divisi') {
            document.getElementById(selectedValue).style.display = 'block';
            document.getElementById('jabatan').style.display = 'none';
        }
    });
    function toggleButtonVisibility() {
        var checkedCount = $('input[type="checkbox"]:checked').length;
        // console.log(checkedCount);
        if (checkedCount > 0) {
            $('#quick_').show(300);
        } else {
            $('#quick_').hide(300);
        }
    }
    $('#myTable tbody').on('change', 'input[type="checkbox"]', function () {
        toggleButtonVisibility();
    });

    $(document).on("click",".hapus-data", function() {
        var idKaryawan  = $(this).data('id_karyawan');
        var NamaKaryawan  = $(this).data('nama_karyawan');
        var url_delete  = "delete-data/"+idKaryawan;
        console.log("ini adalah ID Karyawan: " + idKaryawan);
        console.log("ini adalah Url Hapus data : " + url_delete);
        Swal.fire({
            title: "Hapus karyawan : "+NamaKaryawan+" ?",
            text: "Anda akan kehilangan semua data dari karyawan ini ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Kembali",
            confirmButtonText: "Ya hapus!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url :url_delete,
                    data :{ id : idKaryawan},
                    type : "DELETE",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Sedang menghapus!",
                            html: "Mohon tunggu ",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                    },success: function(s) {
                        if(s.status == true) {
                            Swal.fire({
                                title: "Sukses!",
                                text: "Karyawan berhasil dihapus.",
                                icon: "success"
                            });
                        }else{
                            Swal.fire({
                                title: "Error!",
                                text: "Karyawan gagal dihapus",
                                icon: "error"
                            });
                            console.log(s);
                        }
                    },error : function(e) {
                        Swal.fire({
                                title: "Error!",
                                text: "Hubungi Tim IT.",
                                icon: "error"
                            });
                        console.log(e);
                    },complete: function() {
                        $("#myTable").DataTable().ajax.reload();
                    }
                });
            }
        });

    })

</script>
@endpush
@endsection
