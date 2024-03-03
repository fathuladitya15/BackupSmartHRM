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
        data: 'disetujui_oleh',
        name: 'disetujui_oleh',
        orderable: false,
        searchable: false
    },{
        data: 'disetujui_pada',
        name: 'disetujui_pada',
        orderable: false,
        searchable: false
    },  {
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
