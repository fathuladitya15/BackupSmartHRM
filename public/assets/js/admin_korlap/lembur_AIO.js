var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url :   url_data ,
        data : function(d) {
            d.from_date = $("input[name='from_date']").val();
            d.to_date = $("input[name='to_date']").val();
        }
    },
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'tanggal_lembur',
        name: 'tanggal_lembur',
        orderable: false,
        searchable: false
    },{
        data: 'id_karyawan',
        name: 'id_karyawan',
        orderable: false,
        searchable: false
    },
    {
        data: 'nama_karyawan',
        name: 'nama_karyawan',
        orderable: false,
        searchable: false
    },
    {
        data: 'divisi',
        name: 'divisi',
        orderable: false,
        searchable: false
    },
    {
        data: 'jabatan',
        name: 'jabatan',
        orderable: false,
        searchable: false
    },
    {
        data: 'id_shift',
        name: 'id_shift',
        orderable: false,
        searchable: false
    },
    {
        data: 'status',
        name: 'status',
        orderable: false,
        searchable: false
    },
    {
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    }, ]
}) ;

function filter() {
    table.DataTable().ajax.reload();
}

$('#exampleModalCenter').on('hidden.bs.modal', function(e) {
    document.getElementById("image_ttd").src = "";
    document.getElementById("button_ttd").style.display = "block";
    document.getElementById("aksi").style.display = "block";
    document.getElementById("aksi").style.float = "right";
    document.getElementById("exampleModalLongTitle").innerHTML = "Tambah Lembur";

    $(this).find('form').trigger('reset');
    $("#ttd").val(null);
    $("#id_lembur").val(null);

});

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
                $("#id_lembur").val(data.id);
                $("#tanggal_lembur").val(data.tanggal_lembur);
                $("#id_karyawan").val(data.id_karyawan);
                $("#nama_karyawan").val(data.nama_karyawan);
                $("#alasan_lembur").val(data.alasan_lembur);
                $("#divisi").val(data.divisi);
                $("#jabatan").val(data.jabatan);
                $("#jam_mulai_ar").val(data.jam_mulai);
                $("#jam_selesai_ar").val(data.jam_selesai);

                $("#jam_mulai_rl").val(data.jam_mulai_rl);
                $("#jam_selesai_rl").val(data.jam_selesai_rl);

                $("#jam_mulai_la").val(data.jam_mulai_la);
                $("#jam_selesai_la").val(data.jam_selesai_la);

                $("textarea#tugas").val(data.tugas);
                $('#shift option[value="'+data.id_shift+'"]').prop('selected', true);
                $('#shift').prop('disabled',true);

                $("#tanggal_lembur").prop('disabled',true);
                $("#jam_mulai_ar").prop('disabled',true);
                $("#jam_selesai_ar").prop('disabled',true);

                $("#jam_mulai_rl").prop('disabled',true);
                $("#jam_selesai_rl").prop('disabled',true);

                $("#jam_mulai_la").prop('disabled',true);
                $("#jam_selesai_la").prop('disabled',true);
                $("textarea#tugas").prop('disabled',true);
                $("#alasan_lembur").prop('disabled',true);

                document.getElementById("image_ttd_kr").src                 = assets + data.ttd_karyawan;
                document.getElementById("button_ttd").style.display         = "block";
                document.getElementById("exampleModalLongTitle").innerHTML = "Detail Data";
                document.getElementById("nama_ttd_karyawan").innerHTML = data.nama_karyawan;
                $("#nama_ttd_korlap").css( 'margin-top', '-50px !important')

                var para  = document.getElementsByTagName('p')[2];
                para.setAttribute("id","nama_korlap")
                if(data.status == 1) {
                    document.getElementById("button_ttd").style.display         = "none";
                    document.getElementById("aksi").style.display               = "none";
                    document.getElementById("image_ttd").src  = assets + data.ttd_admin_korlap;
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

function upload(idlembur,id_karyawan) {
    // const detail  = document.getElementById("upload_"+idlembur+"");
    // const stoploading = '<i class="menu-icon tf-icons bx bx-file"></i> Upload';
    $("#id_karyawan_upload").val(id_karyawan);
    $("#id_lembur_upload").val(idlembur);
    $("#modalUpload").modal('show');
}

$('#modalUpload').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
});

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
            // console.log(s)
            if(s.status == true) {
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                });
            }else if(s.status == false){
                Swal.fire({
                    title: "File tidak diizinkan !",
                    text: s.errors.file_lembur[0],
                    icon: "error"
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
            // console.log(e)
            Swal.fire({
                title: "Terjadi Kesalahan !",
                text: "Hubungi Tim IT !",
                icon: "error"
            });
        }
    })
})

$('#modalView').on('hidden.bs.modal', function () {
    document.getElementById("pdf_file_lembur").style.display = "none";
    document.getElementById("pdf_file_lembur").src = "";
    document.getElementById("image_file_lembur").src = "";
});

function view(id) {
    const detail  = document.getElementById("view_"+id+"");
    const stoploading = '<i class="menu-icon tf-icons bx bx-folder-open"></i> Lihat File';
    const loading     = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_view,
        type :  "POST",
        data : {id : id },
        beforeSend : function () {
            detail.innerHTML = loading;
            detail.disabled  = true;
        },success : function(s) {
            console.log(s);
            if(s.status == true) {
                if(s.type_file == 'pdf') {
                    document.getElementById("pdf_file_lembur").style.display = "block";
                    document.getElementById("pdf_file_lembur").src = asset + s.links ;
                }else {
                    document.getElementById("pdf_file_lembur").style.display = "none";
                    document.getElementById("image_file_lembur").src =  asset + s.links ;
                }
                $("#modalView").modal("show");
                detail.innerHTML = stoploading;
                detail.disabled = false;
            }
        }, error : function(e) {
            console.log(e)
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

function send(id) {
    const send  = document.getElementById("send_"+id+"");
    const stoploading = '<i class="menu-icon tf-icons bx bx-folder-open"></i> Lihat File';
    const loading     = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_send,
        type: 'POST',
        data: {id : id},
        beforeSend :  function() {
            send.innerHTML = loading;
            send.disabled  = true;
        },success : function(s) {
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
        },error : function(e){
            console.log(e)
            Swal.fire({
                title: "Terjadi Kesalahan !",
                text: "Hubungi Tim IT !",
                icon: "error"
            });
        },complete: function() {
            table.DataTable().ajax.reload();
            send.innerHTML = stoploading;
            send.disabled = false;
        }
    })
}
