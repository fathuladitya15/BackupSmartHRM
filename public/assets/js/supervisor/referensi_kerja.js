var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },  {
        data: 'no_surat',
        name: 'no_surat',
        orderable: false,
        searchable: false
    },
    {
        data: 'id_karyawan',
        name: 'id_karyawan',
        orderable: false,
        searchable: false
    },{
        data: 'nama_karyawan',
        name: 'nama_karyawan',
        orderable: false,
        searchable: false
    },  {
        data: 'awal_masuk',
        name: 'awal_masuk',
        orderable: false,
        searchable: false
    },  {
        data: 'akhir_masuk',
        name: 'akhir_masuk',
        orderable: false,
        searchable: false
    }, {
        data: 'tanggal_pembuatan',
        name: 'tanggal_pembuatan',
        orderable: false,
        searchable: false
    }, {
        data: 'keterangan',
        name: 'keterangan',
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


$("#Add").submit(function (e) {
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
            console.log(e)
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
