var table  = $('#myTable').dataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url :   url_data ,
        data : function(d) {
            d.from_date = $("input[name='from_date']").val();
            d.to_date = $("input[name='to_date']").val();
        }
    },
    columns: [{
        data: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'tanggal_lembur',
        name: 'tanggal_lembur',
        orderable: false,
        searchable: false
    },{
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
    },
    {
        data: 'divisi',
        name: 'divisi',
        orderable: false,
        searchable: false
    },
    {
        data: 'jabatan',
        name: 'jabatan',
        orderable: false,
        searchable: false
    },
    {
        data: 'id_shift',
        name: 'id_shift',
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

$('#modalView').on('hidden.bs.modal', function () {
    document.getElementById("pdf_file_lembur").style.display = "none";
    document.getElementById("pdf_file_lembur").src = "";
    document.getElementById("image_file_lembur").src = "";
});

function view(id) {
    const detail  = document.getElementById("view_"+id+"");
    const stoploading = '<i class="menu-icon tf-icons bx bx-folder-open"></i> Lihat File';
    const loading     = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
    $.ajax({
        url : url_view,
        type :  "POST",
        data : {id : id },
        beforeSend : function () {
            detail.innerHTML = loading;
            detail.disabled  = true;
        },success : function(s) {
            console.log(s);
            if(s.status == true) {
                if(s.type_file == 'pdf') {
                    document.getElementById("pdf_file_lembur").style.display = "block";
                    document.getElementById("pdf_file_lembur").src = asset + s.links ;
                }else {
                    document.getElementById("pdf_file_lembur").style.display = "none";
                    document.getElementById("image_file_lembur").src =  asset + s.links ;
                }
                $("#modalView").modal("show");
                detail.innerHTML = stoploading;
                detail.disabled = false;
            }
        }, error : function(e) {
            console.log(e)
            Swal.fire({
                title: "Terjadi Kesalahan !",
                text: "Hubungi Tim IT !",
                icon: "error"
            });
        }, complete :  function() {
            detail.innerHTML = stoploading;
            detail.disabled = false;
        }
    })
}
