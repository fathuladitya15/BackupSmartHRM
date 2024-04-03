@extends('vBase')
@section('title')
{{ 'Data Karyawan' }}
@endsection

@push('css')
    <style>
        .black {
            color: black;

        }
        h4 {
            color: white;
        }
        h5 {
            color: white;
        }
        .circular_image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            background-color: blue;
            /* commented for demo
            float: left;
            margin-left: 125px;
            margin-top: 20px;
            */

            /*for demo*/
            display:inline-block;
            vertical-align:middle;
        }
        .circular_image img{
            width:100%;
        }
        .card-title {
            padding-right: 30px;
            border-left-width: 30px;
            padding-left: 30px;

        }
        .card-header {
            background-color:#47b4f5;
            margin-bottom: 20px;
        }

        .swal2-container {
            z-index: 999999;
        };
    </style>

@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan /</span>Kelola Karyawan </h4>
<br>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Karyawan</h5>
            </div>
            <div class="card-title" style="float: right;">
                <button id="setujuiBtn" class="btn btn-success" style="display:none;"><i class="bx bx-check-circle"></i> Setujui Karyawan</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th><input type="checkbox" class="check-all"></th>
                            <th>face id</th>
                            <th>id karyawan</th>
                            <th>photo</th>
                            <th>Nama</th>
                            <th>divisi</th>
                            <th>Jabatan</th>
                            <th>Gol Karyawan</th>
                            <th>tgl Bergabung</th>
                            <th>Selesai Kontrak</th>
                            <th>disetujui oleh</th>
                            <th>disetujui pada</th>
                            <th>disetujui </th>
                            <th>status akun</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    var url_data  = "{{ route('data-kr-spv') }}";
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
        {
            data : 'check_box',
            name : 'check_box',
            orderable : false,
            searchable : false,
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
            data: 'acc',
            name: 'acc',
            orderable: false,
            searchable: false
        },
        {
            data: 'status_karyawan',
            name: 'status_karyawan',
            orderable: false,
            searchable: false
        }, ]
    }) ;

    // Handle checkbox change event
    $('#myTable tbody').on('change', '.select-checkbox', function(){
        if($(this).is(":checked")) {
            $('#setujuiBtn').show(200);
        } else {
            if($('.select-checkbox:checked').length == 0) {
                $('#setujuiBtn').hide(200);
            }
        }
    });

    $("#setujuiBtn").click(function() {
        var selectedIds = [];
        $('.select-checkbox:checked').each(function(){
                selectedIds.push($(this).val());
            });

        // Send selectedIds to controller using Ajax
        $.ajax({
            url: "{{ route('data-kr-acc') }}",
            type: "POST",
            data: {ids: selectedIds},
            beforeSend: function() {
                Swal.fire({
                    title: "Mohon Tunggu ... !",
                    html: "Jangan Tinggalkan Halaman ini <b></b> ",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            },
            success: function(s){
                if(s.status == true) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });
                }else {
                    Swal.fire({
                        title: "Terjadi Kesalahan",
                        text: "Hubungi Tim IT",
                        icon: "error"
                    });
                }
            },error : function(e) {
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
                $('#setujuiBtn').hide(0);
            }
        });
    })

    $(".check-all").change(function() {
        $(".select-checkbox").prop('checked', $(this).prop("checked"));
        if($(this).is(':checked')){
            $('#setujuiBtn').show(200);
        }else {
            $('#setujuiBtn').hide(200);
        }
    })

    // Add event listener to the "check all" checkbox
    // checkAll.addEventListener('change',alert("TES"));

</script>
@endpush
