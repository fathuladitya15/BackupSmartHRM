if (roles == 'karyawan') {
    var columns = [{
        data: 'DT_RowIndex',
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
        data: 'lokasi_absen_masuk',
        name: 'lokasi_absen_masuk',
        orderable: false,
        searchable: false
    },{
        data: 'detail_lokasi_absen_masuk',
        name: 'detail_lokasi_absen_masuk',
        orderable: false,
        searchable: false
    },
    {
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
    },
    {
        data: 'lokasi_absen_plg',
        name: 'lokasi_absen_plg',
        orderable: false,
        searchable: false
    },{
        data: 'detail_lokasi_absen_plg',
        name: 'detail_lokasi_absen_plg',
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
    },]
}else {
    var columns = [{
        data: 'DT_RowIndex',
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
        data: 'lokasi_absen_masuk',
        name: 'lokasi_absen_masuk',
        orderable: false,
        searchable: false
    },{
        data: 'detail_lokasi_absen_masuk',
        name: 'detail_lokasi_absen_masuk',
        orderable: false,
        searchable: false
    },{
        data: 'jam_keluar',
        name: 'jam_keluar',
        orderable: false,
        searchable: false
    },
    {
        data: 'lokasi_absen_plg',
        name: 'lokasi_absen_plg',
        orderable: false,
        searchable: false
    },{
        data: 'detail_lokasi_absen_plg',
        name: 'detail_lokasi_absen_plg',
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
    },]

}



var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_data,
    columns:columns
});
