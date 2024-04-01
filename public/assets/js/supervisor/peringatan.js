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
    },{
        data: 'jabatan',
        name: 'jabatan',
        orderable: false,
        searchable: false
    },  {
        data: 'divisi',
        name: 'divisi',
        orderable: false,
        searchable: false
    },  {
        data: 'lokasi_kerja',
        name: 'lokasi_kerja',
        orderable: false,
        searchable: false
    }, {
        data: 'tipe_peringatan',
        name: 'tipe_peringatan',
        orderable: false,
        searchable: false
    },{

        data: 'tanggal_berlaku',
        name: 'tanggal_berlaku',
        orderable: false,
        searchable: false
    },{

        data: 'tanggal_kadaluarsa',
        name: 'tanggal_kadaluarsa',
        orderable: false,
        searchable: false
    },{

        data: 'nama_pemberi_sp',
        name: 'nama_pemberi_sp',
        orderable: false,
        searchable: false
    },{
        data: 'tanggal_pembuatan',
        name: 'tanggal_pembuatan',
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
    },]
});

$('#exampleModalCenter').on('hidden.bs.modal', function(e) {
    $("#Add  :input").attr("readonly",false);
    $("#Add").trigger("reset");
    document.getElementById('tanggal_start').innerHTML = '...';
    document.getElementById('tanggal_end').innerHTML = '...';

});

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
                // change title modal
                document.getElementById("exampleModalLongTitle").innerHTML = "Detail Data";
                // Read only all input
                $("#Add  :input").attr("readonly",true);
                $("#id_peringatan").val(data.id);
                $("#id_karyawan").val(data.karyawan_id + " - " + data.nama_karyawan);

                // set Value
                $("#no_sp").val(data.no_surat);
                $("#divisi").val(data.divisi);
                $("#jabatan").val(data.jabatan);
                $("#mulai_dari").val(data.tanggal_berlaku);
                $("#sampai_dengan").val(data.tanggal_kadaluarsa);
                $('#tipe_peringatan option[value="'+data.tipe_peringatan+'"]').prop('selected', true);
                $("textarea#alasan_peringatan").val(data.alasan);
                $("#nama_pemberi").val(data.nama_pemberi_sp);
                $("#lokasi_kerja").val(s.lokasi_kerja);
                // block
                // document.getElementById("aksi").style.display = 'none';
                document.getElementById('tanggal_start').innerHTML = data.tanggal_berlaku;
                document.getElementById('tanggal_end').innerHTML = data.tanggal_kadaluarsa;

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

function kirim_spv(id) {
    Swal.fire({
            title: "Kirim ke supervisor ?",
            text: "Anda akan mengirimkan surat peringatan ini ke supervisor untuk direview",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Kembali",
            confirmButtonText: "Lanjutkan"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : url_update,
                    data: {id:id},
                    type: "POST",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Mengirim data !",
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
