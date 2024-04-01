var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    }, {
        data: 'tanggal_pembuatan',
        name: 'tanggal_pembuatan',
    },{
        data: 'no_surat',
        name: 'no_surat',
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
    }, {
        data: 'jam_keluar',
        name: 'jam_keluar',
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
