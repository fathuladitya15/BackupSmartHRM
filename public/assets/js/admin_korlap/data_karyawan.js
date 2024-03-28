var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [
        {
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
    {
        data:'face_id',
        name:'face_id',
        orderable: false,
        searchable: false
    },
    {
        data:'id_karyawan',
        name:'id_karyawan',
        orderable: false,
        searchable: false
    },
    {
        data:'photo',
        name:'photo',
        orderable: false,
        searchable: false
    },
    {
        data: 'name',
        name: 'name',
        orderable: false,
        searchable: false
    },
    {
        data: 'nama_divisi',
        name: 'nama_divisi',
        orderable: false,
        searchable: false
    },
    {
        data: 'nama_jabatan',
        name: 'nama_jabatan',
        orderable: false,
        searchable: false
    },
    {
        data: 'gol_karyawan',
        name: 'gol_karyawan',
        orderable: false,
        searchable: false
    },
    {
        data: 'join_date',
        name: 'join_date',
        orderable: false,
        searchable: false
    },
    {
        data: 'end_date',
        name: 'end_date',
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
        data: 'disetujui_pada',
        name: 'disetujui_pada',
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
        data: 'status_karyawan',
        name: 'status_karyawan',
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


$("#ImportKaryawan").submit(function (e) {
    e.preventDefault();
    var formdata  = new FormData(this);
    $.ajax({
        url: $(this).attr('action'),
        data :formdata,
        type: "POST",
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('#modalUpload').modal('hide');
            Swal.fire({
                title: "Sedang Import Data !",
                html: "Jangan Tinggalkan Halaman ini <b></b> ",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

        },success: function(s) {
            var pesan = s.status;
            if(pesan == true) {
                Swal.fire({
                    title: s.title,
                    text: s.pesan,
                    icon: "success"
                  });
            }else {
                Swal.fire({
                    title: "Opps !",
                    text: "Import Data Gagal!",
                    icon: "errror"
                  });
            }
        },
        error: function(e) {
            var errors = '';
            $.each(e.responseJSON.errors, function(key, value) {
                errors += value + '<br>'; // Membuat daftar pesan kesalahan
            });
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: errors
            });
        },complete: function() {
            table.DataTable().ajax.reload();
        }
    })
})
