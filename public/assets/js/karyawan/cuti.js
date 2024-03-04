var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },  {
        data: 'start_date',
        name: 'start_date',
    },
    {
        data: 'end_date',
        name: 'end_date',
    },{
        data: 'kategori_cuti',
        name: 'kategori_cuti',
    },
    {
        data: 'alasan',
        name: 'alasan',
    },  {
        data: 'periode_cuti',
        name: 'periode_cuti',
    },  {
        data: 'sisa_cuti',
        name: 'sisa_cuti',
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

$('#exampleModalCenter').on('hidden.bs.modal', function(e) {

    $("#Add :input").prop("readonly",false);
    $("#divisi").prop("readonly",true);
    $("#jabatan").prop("readonly",true);
    $("#total_cuti").prop("readonly",true);
    $("#id_karyawan").prop("readonly",true);
    $("#sisa_cuti").prop("readonly",true)
    $(this).find('form').trigger('reset');
    $("#ttd").val(null);
    $("#id_cuti").val(null);
    document.getElementById("aksi").style.display = 'block';
    document.getElementById("aksi").style.float = 'right';
    document.getElementById("form-ttd").style.display = 'block';


});

let start_date,end_date;

$("#start_date").change(function(){
    start_date = $(this).val();
    cuti_yang_diambil(start_date,end_date);
})

$("#end_date").change(function(){
    end_date = $(this).val();
    cuti_yang_diambil(start_date,end_date);
})

function cuti_yang_diambil(start_date , end_date) {
    var batas_hari = $("#total_cuti").val();
    $.ajax({
        url : url_validate_jumlah_cuti,
        type: "POST",
        data : {start_date : start_date,end_date : end_date,batas_hari : batas_hari},
        success : function(s) {
            // console.log(s);
            document.getElementById("total_cuti_hari").value = s.total_cuti;
            if(s.status == false) {
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "warning"
                });
            }
        },error : function(e) {
        },complete : function() {

        }
    })

}

$("#Add").submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: url_save,
        data: $(this).serialize(),
        type: "POST",
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
            console.log(s)
            if(s.status == true) {
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                });
                $("#exampleModalCenter").modal('hide');

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
            $("#Add :input").prop("readonly",true);

        }, success: function(s) {
            var data = s.data;
            if(s.status == true) {
                $("#exampleModalCenter").modal("show");
                $("#id_cuti").val(data.id);
                $("#id_karyawan").val(data.id_karyawan + " - " +data.nama_karyawan)
                $("#divisi").val(data.divisi)
                $("#jabatan").val(data.jabatan)
                $("#start_date").val(data.start_date)
                $("#end_date").val(data.end_date)
                $("#sisa_cuti").val(data.jumlah_cuti)
                $("#total_cuti").val(data.ambil_cuti)
                $("textarea#alasan_cuti").val(data.alasan);
                $("#kategori_cuti").val(data.kategori_cuti);
                if(data.status == 4) {
                    document.getElementById("aksi").style.display = 'none';
                    document.getElementById("form-ttd").style.display = 'none';
                }
                console.log(data)
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

