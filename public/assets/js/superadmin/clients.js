var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: url_get_data,
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },{
        data: 'nama_client',
        name: 'nama_client',
        orderable: false,
        searchable: false
    },{
        data: 'aksi',
        name: 'aksi',
        orderable: false,
        searchable: false
    }, ]
}) ;

$('#Add').submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: url_simpan_data,
        data:$(this).serialize(),
        type:"POST",
        beforeSend:function() {
            Swal.fire({
                title: "Sedang memperoses data !",
                html: "Jangan Tinggalkan Halaman ini <b></b> ",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        },success: function(s) {
            console.log(s)
            Swal.fire('tes','tes','suuccess');
        },error: function(e) {
            console.log(e)
        },complete: function () {

        }
    })
})
