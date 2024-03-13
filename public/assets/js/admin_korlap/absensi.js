
var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
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
    },{
        data: 'jabatan',
        name: 'jabatan',
        orderable: false,
        searchable: false
    },{
        data: 'divisi',
        name: 'divisi',
        orderable: false,
        searchable: false
    },{
        data: 'tanggal',
        name: 'tanggal',
        orderable: false,
        searchable: false
    },{
        data: 'jam_masuk',
        name: 'jam_masuk',
        orderable: false,
        searchable: false
    },{
        data: 'mulai_istirahat',
        name: 'mulai_istirahat',
        orderable: false,
        searchable: false
    },{
        data: 'selesai_istirahat',
        name: 'selesai_istirahat',
        orderable: false,
        searchable: false
    },{
        data: 'jam_keluar',
        name: 'jam_keluar',
        orderable: false,
        searchable: false
    },{
        data: 'shift',
        name: 'shift',
        orderable: false,
        searchable: false
    },{
        data: 'catatan',
        name: 'catatan',
        orderable: false,
        searchable: false
    },{
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    },]
});
