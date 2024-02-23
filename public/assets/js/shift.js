var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_get_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },{
        data: 'type_shift',
        name: 'type_shift',
        orderable: false,
        searchable: false
    },{
        data: 'jam_kerja',
        name: 'jam_kerja',
        orderable: false,
        searchable: false
    },{
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    }, ]
}) ;

$('#formTambahShift').submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: url_simpan_data,
        data:$(this).serialize(),
        type:"POST",
        beforeSend:function() {
            Swal.fire({
                title: "Sedang memperoses data !",
                html: "Jangan Tinggalkan Halaman ini <b></b> ",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        },success: function(s) {
            console.log(s)
            if(s.status == true) {
                Swal.fire({
                    title : s.title,
                    text : s.pesan,
                    icon : "success"
                });

            }else {
                Swal.fire({
                    title: "Terjadi Kesalahan!",
                    text : "Hubungi Tim IT Terkait !" ,
                    icon : "error"
                });
            }
        },error: function(e) {
            console.log(e)
            Swal.fire({
                title: "Terjadi Kesalahan!",
                text : "Hubungi Tim IT Terkait !" ,
                icon : "error"
            });
        },complete: function () {
            table.DataTable().ajax.reload();
            $('#TambahShift').modal('hide');

        }
    })
})

function edit(id) {
    const edit          = document.getElementById("edit_"+id+"");
    const stoploading   = '<i class="bx bx-edit-alt"></i>Edit';
    const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_get_datas,
        data: {id : id},
        type: "POST",
        beforeSend: function() {
            edit.innerHTML  = loading;
            edit.disabled   = true;
        },success: function(s) {
            $('#TambahShift').modal("show");
            document.getElementById("exampleModalLongTitle").innerHTML = "Edit Shift";
            $('#nama_shift').val(s.type);
            $("#ke").val(s.ke);
            $("#waktu_mulai").val(s.waktu_mulai);
            $("#waktu_selesai").val(s.waktu_selesai);
            $('#id').val(s.id);
            // document.getElementById("Add").id = "Edit";
        },error : function(e) {
            console.log(e);
            Swal.fire({
                title: "Terjadi Kesalahan!",
                text : "Hubungi Tim IT Terkait !" ,
                icon : "error"
            });
        },complete: function() {
            table.DataTable().ajax.reload();

            edit.innerHTML = stoploading;
            edit.disabled  = false;
        }
    });
}



function hapus(id) {
    Swal.fire({
            title: "Anda yakin ingin mengahapus Shift ini ?",
            text: "Anda akan kehilangan data dan terjadi akan error apabila Shift sudah digunakan pada beberapa karyawan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Kembali",
            confirmButtonText: "Hapus Shift !"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : url_delete_data,
                    data: {id:id},
                    type: "DELETE",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Sedang menghapus data !",
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
                                title : s.title,
                                text : s.pesan,
                                icon : "success"
                            });
                        }else {
                            Swal.fire({
                                title: "Terjadi Kesalahan!",
                                text : "Hubungi Tim IT Terkait !" ,
                                icon : "error"
                            });
                        }
                    },error : function(e) {
                        Swal.fire({
                            title: "Terjadi Kesalahan!",
                            text : "Hubungi Tim IT Terkait !" ,
                            icon : "error"
                        });
                    },complete: function () {
                        table.DataTable().ajax.reload();
                    }
                })
            }
    });
}

$('#TambahShift').on('hidden.bs.modal', function(e) {
    document.getElementById("exampleModalLongTitle").innerHTML = "Tambah Shift";
    $(this).find('form').trigger('reset');
    $("#id").val(null);

  });
