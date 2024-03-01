
var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },  {
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
        data: 'tanggal_pembuatan',
        name: 'tanggal_pembuatan',
        orderable: false,
        searchable: false
    }, {
        data: 'jam_keluar',
        name: 'jam_keluar',
        orderable: false,
        searchable: false
    },{

        data: 'jam_masuk',
        name: 'jam_masuk',
        orderable: false,
        searchable: false
    },{

        data: 'alasan',
        name: 'alasan',
        orderable: false,
        searchable: false
    },{

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
    },]
});
$(':radio:not(:checked)').attr('disabled', true);

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
                $("#divisi").val(data.divisi);
                $("#jabatan").val(data.jabatan);

                $('#jam_keluar').val(data.jam_keluar);
                $('#jam_masuk').val(data.jam_masuk);
                $("textarea#alasan").val(data.alasan)
                $("#tanggal").val(data.tanggal_pembuatan);

                if(data.kembali == 0) {
                    $("#g_kembali").attr('checked',true);
                }else {
                    $("#g_tidak_kembali").attr('checked',true);
                }
                if(data.ttd_direktur == null) {
                    document.getElementById("button_ttd").style.display = "block";
                    document.getElementById("aksi").style.display = "block";
                }else {
                    $("ttd").val(1);
                    document.getElementById("button_ttd").style.display = "none";
                    document.getElementById("image_ttd").src = assets + data.ttd_mengetahui;
                }
                document.getElementById("exampleModalLongTitle").innerHTML = "Detail Data";
                // if(data.status != 0) {

                // }
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

function acc(id) {
    const acc          = document.getElementById("acc_"+id+"");
    const stoploading   = '<i class="bx bx-check"></i>Setujui';
    const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';

    $.ajax({
        url: url_acc,
        data: {id : id} ,
        type: "POST",
        beforeSend:function(){
            acc.innerHTML = loading;
            acc.disabled = true;
        },success: function(s) {
            if(s.status == true) {
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                });
            }else {
                Swal.fire({
                    title: "Terjadi kesalahan",
                    text: "Hubungi tim IT",
                    icon: "error"
                });
            }
        }, error :function(e) {  Swal.fire({
            title: "Terjadi kesalahan",
            text: "Hubungi tim IT",
            icon: "error"
        });
        }, complete : function() {
            acc.innerHTML = stoploading;
            acc.disabled = false;
            table.DataTable().ajax.reload();
        }
    })
}


