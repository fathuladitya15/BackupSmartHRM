@extends('vBase')
@section('title') {{ 'Kelola Lembur' }} @endsection
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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lembur /</span>Data Lembur</h4>
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
                <h5 class="mb-0">Data Lembur</h5>
            </div>
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Lembur
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Tugas dikerjakan</th>
                            <th>Mulai Jam</th>
                            <th>Sampai Jam</th>
                            <th>Jumlah Jam</th>
                            <th>Status</th>
                            <th>Disetujui Oleh</th>
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
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Lembur</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <table class="table ">
                            <tbody>
                                <tr>
                                    <td width="100">  <img src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" alt="" width="90"> </td>
                                    <td style="color: black; text-align:center">PT Proven Force Indonesia</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="color: black; text-align:center">Form Intruksi Lembur</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="tanggal_lembur" id="tanggal_lembur" type="date" value="">

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Nama Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="nama_karyawan" id="nama_karyawan" type="text" value="{{ Auth::user()->name }}" readonly>
                            <input class="form-control" required="required"  name="id_karyawan" id="id_karyawan" type="hidden" value="{{ Auth::user()->id_karyawan }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jabatan" id="jabatan" type="text" value="{{ $jabatan }}"  readonly>

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="divisi" id="divisi" type="text" value="{{ $divisi }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Lokasi Kerja</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="lokasi_kerja" id="lokasi_kerja" type="text" value="{{ $lokasi_kerja }}" readonly >
                            <input class="form-control" required="required" name="id_client" id="id_client" type="hidden" value="{{ Auth::user()->id_client }}" readonly >

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Alasan Lembur</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="alasan_lembur" id="alasan_lembur" type="text" value="" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jam Mulai</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jam_mulai" id="jam_mulai" type="time"  >
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Jam Selesai</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jam_selesai" id="jam_selesai" type="time"  >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tugas Yang Dikerjakan</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea name="tugas" id="tugas" cols="1" rows="1" class="form-control" required></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Jumlah Jam</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jumlah_jam" id="jumlah_jam" type="text" readonly >

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <p>
                                Petunjuk pengisian form Jam : <br>
                                *) AM Berlaku mulai dari 00.00 WIB sampai dengan 11:59 WIB. <br>
                                *) PM Berlaku mulai dari 12.00 WIB sampai dengan 23.59 WIB. <br>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                            <a href="javascript:void(0)" onclick="ttd('{{ Auth::user()->id_karyawan }}')" class="btn btn-primary btn-sm" id="button_ttd">Tanda Tangani</a>
                            <img class="image_ttd" src="" alt="" id="image_ttd" width="100"> <br  />
                            <div class="" style="">
                                <p id="">( {{ Auth::user()->name }} )</p>
                            </div>
                            <input type="hidden" name="ttd" value="" id="ttd" required >
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
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('data-table-kr-project-internal',['hash' => HashVariable(Auth::user()->id_karyawan)]) }}";
    var url_save    = "{{ route('lembur-save') }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var assets      = "{{ asset('') }}";
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
            data: 'tanggal_lembur',
            name: 'tanggal_lembur',
            orderable: false,
            searchable: false
        },
        {
            data: 'tugas',
            name: 'tugas',
            orderable: false,
        },
        {
            data: 'jam_mulai',
            name: 'jam_mulai',
            orderable: false,
            searchable: false
        },
        {
            data: 'jam_selesai',
            name: 'jam_selesai',
            orderable: false,
            searchable: false
        },
        {
            data: 'total_jam',
            name: 'total_jam',
            orderable: false,
            searchable: false
        },
        {
            data: 'status',
            name: 'status',
            orderable: false,
        },
        {
            data: 'disetujui_oleh',
            name: 'disetujui_oleh',
            orderable: false,
        },
        {
            data: 'aksi',
            name: 'aksi',
            orderable: false,
            searchable: false
        }, ]
    }) ;

    $('#exampleModalCenter').on('hidden.bs.modal', function(e) {

        var tanggalInput        = document.getElementById("tanggal_lembur");
        var JamMulaiInput       = document.getElementById("jam_mulai");
        var JamSelesaiInput     = document.getElementById("jam_selesai");
        var AlasanInput         = document.getElementById("alasan_lembur");
        var TugasInput          = document.getElementById("tugas");


        tanggalInput.disabled = false;
        JamMulaiInput.disabled = false;
        JamSelesaiInput.disabled = false;
        AlasanInput.disabled = false;
        TugasInput.disabled = false;

        document.getElementById("image_ttd").src = "";
        document.getElementById("button_ttd").style.display = "block";
        document.getElementById("aksi").style.display = "block";
        document.getElementById("aksi").style.float = "right";
        document.getElementById("exampleModalLongTitle").innerHTML = "Tambah Lembur";

        $(this).find('form').trigger('reset');
        $("#ttd").val(null);
        $("#id_lembur").val(null);

    });

    $("#Add").submit(function(e) {
        e.preventDefault();
        var ttd = $("#ttd").val();
        if(ttd == "") {
            Swal.fire({
                title: "Belum ditandatangani!",
                text : "Klik tanda tangan terlebih dahulu!" ,
                icon : "info"
            });
        }else {
            $.ajax({
                url : url_save,
                data:new FormData(this),
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
                    console.log(s)
                }, error : function(e) {
                    console.log(e)

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
        }
    })

    function detail(id) {
        const edit              = document.getElementById("edit_"+id+"");
        const stoploading       = '<i class="bx bx-edit-alt"></i>Edit';
        const loading           = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
        var tanggalInput        = document.getElementById("tanggal_lembur");
        var JamMulaiInput       = document.getElementById("jam_mulai");
        var JamSelesaiInput     = document.getElementById("jam_selesai");
        var AlasanInput         = document.getElementById("alasan_lembur");
        var TugasInput          = document.getElementById("tugas");

        $.ajax({
            url : url_detail,
            data: {id:id},
            type: "POST",
            beforeSend: function() {
                edit.innerHTML = loading;
                edit.disabled = true;
                tanggalInput.disabled = true;
                JamMulaiInput.disabled = true;
                JamSelesaiInput.disabled = true;
                AlasanInput.disabled = true;
                TugasInput.disabled = true;
            }, success: function(s) {
                var data = s.data;
                if(s.status == true) {
                    $("#exampleModalCenter").modal("show");
                    $("#id_lembur").val(data.id);
                    $("#tanggal_lembur").val(data.tanggal_lembur);
                    $("#alasan_lembur").val(data.alasan_lembur);
                    $("#jam_mulai").val(data.jam_mulai);
                    $("#jam_selesai").val(data.jam_selesai);
                    $("textarea#tugas").val(data.tugas);
                    $("#jumlah_jam").val(data.total_jam);
                    $("#batch").val(data.batch);
                    $("#group").val(data.group);
                    $("ttd").val(1);
                    document.getElementById("image_ttd").src = assets + data.ttd_karyawan;
                    document.getElementById("button_ttd").style.display = "none";
                    document.getElementById("exampleModalLongTitle").innerHTML = "Detail Data";
                    if(data.status == 0) {
                        document.getElementById("aksi").style.display = "none";
                    }
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

    function hitungJam(){
        var tanggal = document.getElementById("tanggal_lembur").value;
        var mulai   = document.getElementById("jam_mulai").value;
        var selesai = document.getElementById("jam_selesai").value;

        // Gabungkan tanggal dan waktu mulai
        var waktuMulaiLembur    = new Date(tanggal + "T" + mulai);

        // Gabungkan tanggal dan waktu selesai
        var waktuSelesaiLembur  = new Date(tanggal + "T" + selesai);


        if (isNaN(waktuMulaiLembur) || isNaN(waktuSelesaiLembur)) {
            document.getElementById("jumlah_jam").value = "Mohon masukkan waktu mulai dan selesai yang valid.";
            return;
        }

        // Hitung selisih waktu dalam milidetik
        var selisih             = waktuSelesaiLembur - waktuMulaiLembur;

        // Validasi hasil negatif (minus)
        if (selisih < 0) {
            document.getElementById("jumlah_jam").value = "Waktu selesai harus setelah waktu mulai.";
            return;
        }

        // Konversi selisih waktu ke dalam jam dan menit
        var jam         = Math.floor(selisih / (1000 * 60 * 60));
        var menit       = Math.floor((selisih % (1000 * 60 * 60)) / (1000 * 60));

        var result;

        if(menit == 0) {
            result = jam + " Jam";
        }else if(jam == 0) {
            result = menit + " menit";
        }
        else {
            result = jam + " Jam " + menit +" menit.";
        }

        // var result      = "Lembur selama " + jam + " jam " + menit + " menit";

        document.getElementById("jumlah_jam").value = result;
    }

    document.getElementById('tanggal_lembur').addEventListener("change",hitungJam);
    document.getElementById('jam_mulai').addEventListener("change",hitungJam);
    document.getElementById('jam_selesai').addEventListener("change",hitungJam);

</script>

@endpush
