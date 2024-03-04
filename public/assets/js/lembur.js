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
        searchable: false
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
        searchable: false
    },
    {
        data: 'disetujui_oleh',
        name: 'disetujui_oleh',
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


$('#tanggal_lembur').on('change', function() {
    var tanggal = $(this).val();
    var new_tanggal = new Date(tanggal);
    var fix     = new_tanggal.toString('D/M/Y');
    var month   = new_tanggal.getMonth()
    var years   = new_tanggal.getFullYear()
    var date    = new_tanggal.getDate()

    var str = date + '/' + month + '/' + years;
    dates  = tanggal;
    total_jam(start,end,dates)
});

$('#jam_mulai').on('change',function() {
    start = $(this).val();
    total_jam(start,end,dates)
})

$('#jam_selesai').on('change',function() {
    end = $(this).val();
    total_jam(start,end,dates)
});

function total_jam(start, end, dates) {
    var time1 = dates+' '+start+':00';
    var time2 = dates+' '+end+':00';
    var date1 = new Date(time1).getHours();
    var date2 = new Date(time2).getHours();

    var hourDiff = date2 - date1;
    $('#jumlah_jam').val(hourDiff)
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
    const stoploading   = '<i class="bx bx-edit-alt"></i>Edit';
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
    // alert(id)
}
