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
        data: 'divisi',
        name: 'divisi',
        orderable: false,
        searchable: false
    },{
        data: 'jabatan',
        name: 'jabatan',
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
        data: 'disetujui_pada',
        name: 'disetujui_pada',
        orderable: false,
        searchable: false
    },{
        data: 'notif',
        name: 'notif',
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
