
var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    }, {
        data: 'karyawan_id',
        name: 'karyawan_id',
        orderable: false,
        searchable: false
    },
    {
        data: 'nama_karyawan',
        name: 'nama_karyawan',
        orderable: false,
        searchable: false
    }, {
        data: 'divisi',
        name: 'divisi',
        orderable: false,
        searchable: false
    }, {
        data: 'jabatan',
        name: 'jabatan',
        orderable: false,
        searchable: false
    },  {
        data: 'alasan',
        name: 'alasan',
        orderable: false,
        searchable: false
    },
    {
        data: 'status',
        name: 'status',
        orderable: false,
        searchable: false
    },{

        data: 'approved',
        name: 'approved',
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

$('#exampleModalCenter').on('hidden.bs.modal', function(e) {
    document.getElementById("image_ttd").src = "";
    document.getElementById("button_ttd").style.display = "block";
    document.getElementById("aksi").style.display = "block";
    document.getElementById("aksi").style.float = "right";
    document.getElementById("exampleModalLongTitle").innerHTML = "Tambah Izin";

    $(this).find('form').trigger('reset');
    $("#ttd").val(null);
    $("#id_izin").val(null);

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
    const edit          = document.getElementById("edit_"+id+"");
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
            var data = s.data;
            if(s.status == true) {
                $("#exampleModalCenter").modal("show");

                $("#id_izin").val(data.id);
                $("#nama_karyawan").val(data.nama_karyawan);
                $("#id_karyawan").val(data.karyawan_id);
                $("#jabatan").val(data.jabatan);
                $("#divisi").val(data.divisi);
                $("#jam_keluar").val(data.jam_keluar);
                $("#jam_masuk").val(data.jam_masuk);
                $("textarea#alasan").val(data.alasan);
                $("#tanggal").val(data.tanggal_pembuatan);

                $("ttd").val(1);

                if(data.ttd_mengetahui == null) {
                    document.getElementById("button_ttd").style.display = "block";
                }else {
                    $("ttd").val(1);
                    document.getElementById("button_ttd").style.display = "none";
                    document.getElementById("image_ttd").src = assets + data.ttd_mengetahui;
                }
                document.getElementById("exampleModalLongTitle").innerHTML = "Detail Data";
                if(data.status != 0) {
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

$('#modalUpload').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
});

function upload(id) {
    const detail  = document.getElementById("upload_"+id+"");
    const stoploading = '<i class="menu-icon tf-icons bx bx-upload"></i> Upload';
    $("#modalUpload").modal('show');
    $("#id_upload").val(id)
}

$("#formUpload").submit(function(e) {
    e.preventDefault()
    var form = document.getElementById('formUpload');
    var formData = new FormData(form);
    $.ajax({
        url : url_upload,
        data : formData,
        type : "POST",
        contentType: false,
        processData: false,
        success :  function(s) {
            console.log(s)
            if(s.status == true) {
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                });
            }else {
                Swal.fire({
                    title: "Terjadi Kesalahan !",
                    text: "Hubungi Tim IT !",
                    icon: "error"
                });
            }
            $("#modalUpload").modal('hide');
            table.DataTable().ajax.reload();
        },error : function(e) {
            console.log(e)
            Swal.fire({
                title: "Terjadi Kesalahan !",
                text: "Hubungi Tim IT !",
                icon: "error"
            });
        }
    })
});

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
