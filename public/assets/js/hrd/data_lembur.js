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
        data: 'tanggal_lembur',
        name: 'tanggal_lembur',
        orderable: false,
        searchable: false
    },
    {
        data: 'tugas',
        name: 'tugas',
        orderable: false,
        searchable: false
    },    {
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
                $("#alasan_lembur").val(data.alasan_lembur);
                $("#jam_mulai").val(data.jam_mulai);
                $("#jam_selesai").val(data.jam_selesai);
                $("textarea#tugas").val(data.tugas);
                $("#jumlah_jam").val(data.total_jam);
                $("#batch").val(data.batch);
                $("#group").val(data.group);
                $("#jabatan").val(data.jabatan);
                $("#divisi").val(data.divisi);
                $("#lokasi_kerja").val(data.lokasi_kerja);
                $("#id_karyawan").val(data.id_karyawan);
                $("#nama_karyawan").val(data.nama_karyawan);
                $("ttd").val(1);
                document.getElementById("button_ttd").style.display         = "block";
                document.getElementById("exampleModalLongTitle").innerHTML  = "Detail Data";
                $("#nama_ttd_korlap").css( 'margin-top', '-50px !important')
                // console.log(data)
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
