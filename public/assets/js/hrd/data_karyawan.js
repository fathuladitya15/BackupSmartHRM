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
    },
    {
        data: 'nama_divisi',
        name: 'nama_divisi',
        orderable: false,
        searchable: false
    },{
        data: 'nama_jabatan',
        name: 'nama_jabatan',
        orderable: false,
        searchable: false
    },
    {
        data: 'kategori',
        name: 'kategori',
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
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    }, ]
}) ;
