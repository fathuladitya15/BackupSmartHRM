<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Dokumen Pendukung</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan-update',['hash' => HashVariable($data_karyawan->id_karyawan)]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <br  />
                    <input type="hidden" name="id_table_karyawan" value="{{ $data_karyawan->id }}">
                    <input type="hidden" name="type" value="data-dokumen">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto KTP</label>
                            <input class="form-control"   name="ktp" type="file" id="ktp">
                            <a href="javascript:void(0)" id="ktp_{{ $data_karyawan->id_karyawan }}" onclick="lihat_file('ktp','{{ $data_karyawan->id_karyawan }}')" class="btn btn-primary btn-sm">Lihat KTP</a>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor KTP</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"    name="n_ktp" type="number" id="n_ktp" value="{{ $data_karyawan->nik }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto NPWP</label>
                            <input class="form-control"   name="npwp" type="file" id="npwp">
                            <a href="javascript:void(0)" id="npwp_{{ $data_karyawan->id_karyawan }}" onclick="lihat_file('npwp','{{ $data_karyawan->id_karyawan }}')" class="btn btn-primary btn-sm">Lihat NPWP</a >

                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor NPWP</label>

                            <input class="form-control"   name="n_npwp" type="text" id="n_npwp" value="{{ $data_karyawan->no_npwp }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto KPJ</label>
                            <input class="form-control"   name="kpj" type="file" id="kpj">
                            <a href="javascript:void(0)" id="kpj_{{ $data_karyawan->id_karyawan }}" onclick="lihat_file('kpj','{{ $data_karyawan->id_karyawan }}')" class="btn btn-primary btn-sm">Lihat KPJ</a>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor KPJ</label>
                            <input class="form-control"   name="n_kpj" type="text" id="n_kpj" value="{{ $data_karyawan->no_kpj }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto JKN</label>
                            <input class="form-control"   name="jkn" type="file" id="jkn">
                            <a href="javascript:void(0)" id="jkn_{{ $data_karyawan->id_karyawan }}" onclick="lihat_file('jkn','{{ $data_karyawan->id_karyawan }}')" class="btn btn-primary btn-sm">Lihat JKN</a>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor JKN</label>
                            <input class="form-control"   name="n_jkn" type="text" id="n_jkn" value="{{ $data_karyawan->jkn }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2" style="float: right">Simpan</button>
                          </div>
                    </div>
                    <br>
                </form>
            </div>
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
    var url_file = "{{ route('karyawan-view-file',['hash'=> HashVariable($data_karyawan->id_karyawan)]) }}";
</script>
<script>

    function lihat_file(tipe_file,id_karyawan) {
        var id_button       = tipe_file+'_'+id_karyawan;
        const button          = document.getElementById(id_button);
        const stoploading   = 'Lihat File '+tipe_file;
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

    $('#modalView').on('hidden.bs.modal', function () {
        document.getElementById("pdf_file").style.display = "none";
        document.getElementById("pdf_file").src = "";
        document.getElementById("image_file").src = "";
        document.getElementById("ModalTitle").innerHTML = "";

    });
</script>
@endpush
