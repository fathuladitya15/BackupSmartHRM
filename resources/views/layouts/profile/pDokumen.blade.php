



<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between" >
        <h5 class="mb-0">Data Dokumen </h5>
    </div>
    <br>
    <div class="card-title">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" style="float: right">
            Tambah / Update File
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama File</th>
                        <th>Jenis File File</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Tambah File -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Upload File</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_filemanager" value="" id="id_filemanager">
                <input type="hidden" class="form-control" id="id_karyawan" name="id_karyawan" value="{{ Auth::user()->id_karyawan }}" readonly>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="id_karyawan">Pilih Jenis File</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="jenis_file" id="jenis_file" class="form-control" required>
                                <option value="">-- Pilih Jenis File --</option>
                                <option value="kpj">KPJ</option>
                                <option value="jkn">JKN</option>
                                <option value="ktp">KTP</option>
                                <option value="cv">CV</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="upload_file">Upload File</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="file" type="file"  id="file">
                        </div>
                    </div>
                </div>

                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="submit_lagi">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL VIEW FILE -->
<div class="modal fade" id="modalView" role="dialog"  >
    <div class="modal-dialog  modal-lg modal-dialog-centered" role="document" >
        <div class="col-lg-12" style="text-align: center;">
            <img src="" alt="" id="image_file" style="width: 50%">
        </div>
        <div class="col-lg-12">
            <iframe src="" width="100%" height="500px" id="pdf_file" frameborder="0"></iframe>
        </div>
    </div>
</div>
<!-- /MODAL VIEW FILE -->
@push('js')

<script>
    var url_data = "{{ route('profile-filemanager') }}";
    var table  = $('#myTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: url_data,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },  {
            data: 'slug',
            name: 'slug',
            orderable: false,
            searchable: false
        },
        {
            data: 'extension',
            name: 'extension',
            orderable: false,
            searchable: false
        },
        {
            data: 'keterangan',
            name: 'keterangan',
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
    var url_file = "{{ route('karyawan-view-file',['hash'=> HashVariable(Auth::user()->id_karyawan)]) }}";
    function lihat_file(tipe_file,id_karyawan) {
        var id_button       = tipe_file+'_'+id_karyawan;
        const button          = document.getElementById(id_button);
        const stoploading   = '<i class="bx bxs-folder-open"></i> Lihat File';
        const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
        // alert(button);
        $.ajax({
            url: url_file,
            data:{
                tipe_file : tipe_file,
                id_karyawan: id_karyawan
            },
            type: "POST",
            beforeSend: function() {
                button.innerHTML = loading
            },success: function(s) {
                if(s.status == true) {
                    $("#modalView").modal('show');
                    if(s.type_file == 'pdf') {
                        document.getElementById("ModalTitle").innerHTML = s.alt;
                        document.getElementById("pdf_file").style.display = "block";
                        document.getElementById("pdf_file").src = s.path ;
                    }else if (s.tipe_file != 'pdf') {
                        document.getElementById("pdf_file").style.display = "none";
                        document.getElementById("image_file").src = s.path;
                    }else {
                        console.log("error");
                    }
                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "info"
                    });
                }
            }, error : function(e) {
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi Tim IT",
                    icon: "info"
                });
            },complete: function() {
                button.innerHTML = stoploading;
            }
        })
    }
    var url  = "{{ route('update_password') }}";
    $("#update_password").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({

            url : url,
            data: formData,
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
            success : function(s) {
                console.log(s);
                if(s.status == true) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });

                }else if(s.status == false) {
                    alert.classList.add("invalid");
                    alert.classList.remove("valid");
                    alert.innerHTML = 'Password Tidak Sesuai';
                }
            },error : function(s) {
                console.log(s);
            }
        })
    })

    var url_save = "{{ route('files-uploads') }}";
    $("#Add").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: url_save,
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
            },success : function(s) {
                // console.log(s)
                if(s.status == true) {
                    if(s.data != 0) {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: "warning",
                            confirmButtonText: "Perbarui Data!",
                            showCancelButton: true,
                            confirmButtonColor: "#0048E8",
                            cancelButtonColor: "#d33",
                            cancelButtonText: "Kembali",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#id_filemanager").val(s.id_filemanager);
                                $("#submit_lagi").trigger('click');
                            }
                        });
                    }else {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: "success"
                        });
                        $("#exampleModalCenter").modal('hide');
                    }

                }else {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "warning"
                    });
                }
            },error: function(e) {
                // console.log(e)
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi tim IT",
                    icon: "error"
                });
            },complete: function() {
                table.DataTable().ajax.reload();
            }
        })
    });
    $('#exampleModalCenter').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
    });

    function blurFunction(val) {
        var id      = "{{ Auth::user()->id_karyawan }}";
        var value   = document.getElementById('input_'+val).value;

        $.ajax({
            url : "{{ route('profile-update') }}",
            data : {id_karyawan : id, dokumen: val , value_dokumen: value},
            type:"POST",
            beforeSend: function() {

            }, success: function(s) {
                console.log(s);
            }  ,error : function(e){
                console.log(e);
            }, complete:function() {

            }
        })

    }
</script>
@endpush
