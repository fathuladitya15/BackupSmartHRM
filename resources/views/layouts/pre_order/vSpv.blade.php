@extends('vBase')
@section('title') {{  'Permintaan Pembelian' }} @endsection


@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.signature.css') }}">


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
    p#nama {
        margin-top: -50px !important;
    }
    .over_text {
        margin-top: -50px !important;
    }
    #button_ttd{
        width: 150px;
    }
</style>

@endpush
@section('content')
<h4 class="fw-bold py-3 mb-4">Permintaan Pembelian</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Permintaan Pembelian </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>No Pre Order</th>
                                <th>Divisi</th>
                                <th>Dibuat Oleh</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
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

<div class="modal fade" id="modalCreate" role="dialog" data-bs-backdrop="static" >
    <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="TitleModal" style="color: black">Detail Permintaan Pembelian </h5>
          <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" id="create" >
            <input type="hidden" name="id" id="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="100">  <img src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" alt="" width="90"> </td>
                                <td style="color: black; text-align:center">PT Proven Force Indonesia</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="color: black; text-align:center">Permintaan Pembelian</td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <td width="200" style="text-align: right">Bulan :</td>
                            <td width="170" style="color: black; text-align:center">
                                <select name="bulan" id="bulan" class="form-control" disabled>
                                    <option value="">-- Pilih Bulan --</option>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                            </td>
                            <td width="50"></td>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Nomor PO</label>
                        <input class="form-control"   name="no_po" id="no_po"  value=""  disabled>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Divisi</label>
                        <input class="form-control"   name="divisi" id="divisi" type="text" disabled>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Tanggal</label>
                        <input class="form-control"  name="tanggal" id="tanggal" type="date" value="" disabled>

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Batas Waktu</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"   name="batas_waktu" id="batas_waktu" type="date" value="" disabled>
                    </div>
                </div>
                <br>
                <div class="row">

                    <label class="col-form-label" for="basic-default-name">Data Barang</label>
                    <hr>
                    <div class="col-lg-2">
                        Nama Barang
                    </div>
                    <div class="col-lg-2">
                        Unit
                    </div>
                    <div class="col-lg-2">Satuan
                    </div>
                    <div class="col-lg-2">Biaya
                    </div>
                    <div class="col-lg-2">Jumlah
                    </div>
                    <div class="col-lg-2">Ket
                    </div>
                </div>
                <br>
                <div class="row" id="form_input_barang" >
                    <div id="barang_input_detail">

                    </div>
                    <input type="hidden" value="0" id="total_semua_barang_detail" name="total_semua_barang">
                </div>
                <br>
                <div class="row">
                    <table class=""  width="100%">
                        <tr>
                            {{-- <td ></td> --}}
                            <td style="float: right" id="total"></td>
                            <td width="18%"></td>
                        </tr>
                    </table>
                </div>
                <br><br>
                {{-- Tanda Tangan --}}
                <br>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal"  class="btn btn-secondary " >
                        Tutup
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset("assets/js/jquery.signature.js") }}"></script>
<script>
    var url_data    = "{{ route('pre-order-data') }}";
    var url_detail  = "{{ route('pre-order-get-data') }}";
    var url_update  = "{{ route('pre-order-update') }}";
    var asset       = "{{ asset('') }}";
    var table  = $('#myTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: url_data,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },{
            data: 'no_po',
            name: 'no_po',
            orderable: false,
            searchable: false
        },{
            data: 'divisi',
            name: 'divisi',
            orderable: false,
            searchable: false
        }, {
            data: 'dibuat_oleh',
            name: 'dibuat_oleh',
            orderable: false,
            searchable: false
        }, {
            data: 'jumlah',
            name: 'jumlah',
            orderable: false,
            searchable: false
        },{
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false
        },{
            data: 'aksi',
            name: 'aksi',
            orderable: false,
            searchable: false
        }, ]
    }) ;

    function edit(id) {
        const detail  = document.getElementById("edit_"+id+"");
        const stoploading = '<i class="bx bx-edit-alt"></i> Detail';
        $.ajax({
            url : url_detail,
            type: "POST",
            data: {id:id},
            beforeSend: function() {
                detail.innerHTML = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
                detail.disabled = true;
            }, success : function (s) {
                var data = s.data_po;
                const div = document.getElementById("form_input_barang");
                const newDiv = document.createElement("div");
                newDiv.setAttribute("id","barang_input_detail");
                div.append(newDiv);
                $("#modalCreate").modal('show');
                $('#bulan option[value="'+data.bulan+'"]').prop('selected', true);
                $("#no_po").val(data.no_po);
                $("#divisi").val(data.divisi);
                $("#tanggal").val(data.tanggal);
                $("#batas_waktu").val(data.batas_waktu);
                $('#barang_input_detail').append(s.barang_po);
                $('#barang_input_detail input').prop('disabled', true);
                $("#total_semua_barang").val(s.total_barang);
                $("#id").val(data.id);

            }, error: function(e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Hubungi Tim IT'
                });
            },complete: function() {
                detail.innerHTML = stoploading;
                detail.disabled = false;
            }
        })
    }
    $("#modalDetail").on("hidden.bs.modal",function() {
        $("#barang_input_detail").remove();
        delete_div("create");
    });

    function delete_div(tipe){

        if(tipe == "create"){
            var total    = $("#total_semua_barang").val() ;
            var id_name  = "#row_" +total;
            var br       = "#br_" +total;

            $(id_name).remove();
            $(br).remove();
            $("#total_semua_barang").val(total - 1);
        }else {
            var value_total     = $("#total_semua_barang_detail").val();
            var set_row_id      = value_total - 1;
            var set_id          = "#r_"+set_row_id;
            $(set_id).remove();
            $("#total_semua_barang_detail").val(set_row_id);
        }
    }

    function check(id){
        Swal.fire({
            title: "Setujui Permintaan ini ?",
            text: "Data akan dilanjutakan ke direktur untuk ditandatangani",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Setujui !"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : url_update,
                    data : {id : id, status : "1"},
                    type: "POST",
                    success: function(s) {
                        console.log(s)
                        var status = s.status;
                        if(status == true) {
                            swal.fire(s.pesan,s.title,'success');
                        }else {
                            swal.fire(s.pesan,s.title,'erorr');
                        }
                        $("#modalCreate").modal('hide');
                        table.DataTable().ajax.reload();
                    }, error : function(e) {
                        console.log(e)
                        swal.fire('Terjadi Kesalahan !','Hubungi Tim IT','error');

                    }
                })
            }
        });
    }
    function reject(id){
        Swal.fire({
            title: "Tolak Permintaan ini ?",
            text: "Data akan dikembalikan ke GA",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Lanjutkan !"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : url_update,
                    data : {id : id, status : "5"},
                    type: "POST",
                    success: function(s) {
                        var status = s.status;
                        console.log(s)
                        if(status == true) {
                            swal.fire(s.pesan,s.title,'success');
                        }else {
                            swal.fire(s.pesan,s.title,'erorr');
                        }
                        $("#modalCreate").modal('hide');
                        table.DataTable().ajax.reload();
                    }, error : function(e) {
                        swal.fire('Terjadi Kesalahan !','Hubungi Tim IT','error');

                    }
                })
            }
        });
    }
</script>
@endpush
