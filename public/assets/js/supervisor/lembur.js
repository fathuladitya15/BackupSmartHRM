var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
        ajax: {
            url: url_data,
            data:function (d) {
                d.from_date = $('input[name="from"]').val();
                d.to_date = $('input[name="to"]').val();
            }
        },
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },{
            data: 'id_karyawan',
            name: 'id_karyawan',
        }, {
            data: 'nama_karyawan',
            name: 'nama_karyawan',
        },  {
            data: 'divisi',
            name: 'divisi',
            orderable: false,
            searchable: false
        }, {
            data: 'jabatan',
            name: 'jabatan',
            orderable: false,
            searchable: false
        },{
            data: 'tanggal_lembur',
            name: 'tanggal_lembur',
            orderable: false,
            searchable: false
        },{
            data: 'tugas',
            name: 'tugas',
            orderable: false,
            searchable: false
        },
        {
            data: 'jam_mulai',
            name: 'jam_mulai',
            orderable: false,
            searchable: false
        }, {
            data: 'jam_selesai',
            name: 'jam_selesai',
            orderable: false,
            searchable: false
        }, {
            data: 'total_jam',
            name: 'total_jam',
            orderable: false,
            searchable: false
        },  {
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false
        }, {
            data: 'aksi',
            name: 'aksi',
            orderable: false,
            searchable: false
        }, ]
    }) ;
