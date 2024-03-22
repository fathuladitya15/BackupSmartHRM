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
            <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate" style="float: right">
                    <i class='bx bx-plus'></i> Buat Permintaan Pembelian
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th>#</th>
                                <th>No Pre Order</th>
                                <th>Divisi</th>
                                <th>Status</th>
                                <th>Disetujui Oleh</th>
                                <th>Ditandatangangi oleh</th>
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
          <h5 class="modal-title" id="TitleModal" style="color: black">Buat Permintaan </h5>
          <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" id="create"  action="{{ route('pre-order-create') }}" method="POST">
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
                                <td style="color: black; text-align:center">Permintaan Pembelian </td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <table>
                        <tr>
                            <td width="200" style="text-align: right">Bulan :  <span class="text-danger pl-1">*</span></td>
                            <td width="170" style="color: black; text-align:center">
                                <select name="bulan" id="bulan" class="form-control" >
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach ($nama_bulan as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>

                                    @endforeach
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
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"   name="no_po" id="no_po"  value="" >

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Divisi</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"   name="divisi" id="divisi" type="text">

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Tanggal</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"  name="tanggal" id="tanggal" type="date" value="" >

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Batas Waktu</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"   name="batas_waktu" id="batas_waktu" type="date" value="" >
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class=" col-form-label" for="basic-default-name">Diajukan Oleh</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"  name="diajukan_oleh" id="diajukan_oleh" type="text" value="" >
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
                <div class="row">
                    <div id="barang_input">
                        <div class="row">
                            <div class="col-lg-2">
                                <input class="form-control"   name="nama_barang[]" id="nama_barang_0" type="text" value="" placeholder="Nama Barang">
                            </div>
                            <div class="col-lg-2">
                                <input class="form-control"   name="unit[]" id="unit_0" type="text" onkeyup="get_total_barang(0)"  value="" placeholder="Unit">
                            </div>
                            <div class="col-lg-2">
                                <input class="form-control"   name="satuan[]" id="satuan_0" type="text" value="" placeholder="Satuan">
                            </div>
                            <div class="col-lg-2">
                                <input class="form-control"   name="biaya[]" id="biaya_0" type="text" value="" onkeyup="get_harga_perbarang(0)" placeholder="Biaya/Unit">
                            </div>
                            <div class="col-lg-2">
                                <input class="form-control"   name="jumlah[]" id="jumlah_0" type="text" value="" placeholder="Jumlah Unit" readonly>
                            </div>
                            <div class="col-lg-2">
                                <input class="form-control"   name="keterangan[]" id="keterangan_0" type="text" value="" placeholder="Keterangan">
                            </div>
                        </div>
                        <br>
                    </div>
                    <input type="hidden" value="0" id="total_semua_barang" name="total_semua_barang">
                    <div class="row">
                        <div class="col-lg-3" style="text-align: center">
                            <button type="button" onclick="tambah_barang('create')" class=" btn btn-primary btn-sm">Tambah Barang</button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" onclick="delete_div('create')" class=" btn btn-danger btn-sm">Hapus</button>
                        </div>
                    </div>

                </div>
                <br>

                <div class="row">
                    <div class="col-lg-12">
                        <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                        <a href="javascript:void(0)" onclick="ttd('{{ Auth::user()->id_karyawan }}')" class="btn btn-primary btn-sm" id="button_ttd">Tanda Tangani</a>
                        <img class="image_ttd" src="" alt="" id="image_ttd" width="100"> <br  />
                        <div class="" style="">
                            <p id=""> {{ Str::title(Auth::user()->name) }} </p>
                        </div>
                        <input type="hidden" name="ttd" value="" id="ttd" required >
                    </div>
                </div>
                <hr>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary simpan" id="btn-save">
                    Kirim Ke Supervisor
                </button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" role="dialog" data-bs-backdrop="static" >
    <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="TitleModal" style="color: black">Detail Permintaan </h5>
          <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" id="detail" action="{{ route('pre-order-update') }}" >
            <input type="hidden" name="id" id="id_detail" value="">
            <input type="hidden" name="status" id="status" value="">
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
                                <td style="color: black; text-align:center">Permintaan Pembelian </td>
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
                                <select name="bulan_detail" id="bulan_detail" class="form-control" >
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
                        <label class=" col-form-label" for="basic-default-name">Nomor Pre Order</label>
                        <input class="form-control"   name="no_po_detail" id="no_po_detail"  value="" >

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Divisi</label>
                        <input class="form-control"   name="divisi_detail" id="divisi_detail" type="text">

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Tanggal</label>
                        <input class="form-control"  name="tanggal_detail" id="tanggal_detail" type="date" value="" >

                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="basic-default-name">Batas Waktu</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control"   name="batas_waktu" id="batas_waktu_detail" type="date" value="" >
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
                <div class="row" id="tombol_tambah_barang">
                    <button type="button" onclick="tambah_barang('detail')" class=" btn btn-primary btn-sm" style="width:150px;">Tambah Barang</button>
                    <button type="button" onclick="delete_div('detail')" class=" btn btn-danger btn-sm" style="width:150px;">Hapus</button>
                    <div class="col-lg-3" style="text-align: center">
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>
                <br>
                <br><br>
                {{-- Tanda Tangan --}}
                <br>
                <div class="row">
                    <div class="col-lg-12">
                        <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                        <img class="image_ttd_detail" src="" alt="" id="image_ttd_detail" width="100"> <br  />
                        <div class="" style="">
                            <p id=""> {{ Str::title(Auth::user()->name) }} </p>
                        </div>
                        <input type="hidden" name="ttd" value="" id="ttd" required >
                    </div>
                </div>
                <hr>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary simpan" id="btn_save_detail">
                    Update Data
                </button>
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
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false
        }, {
            data: 'disetujui_oleh',
            name: 'disetujui_oleh',
            orderable: false,
            searchable: false
        },{
            data: 'ttd_direktur',
            name: 'ttd_direktur',
            orderable: false,
            searchable: false
        },{
            data: 'aksi',
            name: 'aksi',
            orderable: false,
            searchable: false
        }, ]
    }) ;

    $("#create").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url     : $(this).attr('action'),
            data    : $(this).serialize(),
            type    : "POST",
            beforeSend: function() {
                $("#modalCreate").modal('hide');
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }, success : function(s) {
                console.log(s);
                if(s.status == true) {
                    Swal.fire({
                        icon: 'success',
                        title: s.title,
                        text: s.pesan
                    });
                }else {
                    Swal.fire({
                        icon: 'error',
                        title: s.title,
                        text: s.pesan
                    });
                }
            }, error : function (e) {
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
            }
        })
    });

    function edit(id) {
        const detail  = document.getElementById("edit_"+id+"");
        const stoploading = '<i class="bx bx-edit-alt"></i> Edit';
        $.ajax({
            url : url_detail,
            type: "POST",
            data: {id:id},
            beforeSend: function() {
                detail.innerHTML = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';
                detail.disabled = true;
            }, success : function (s) {
                var data = s.data_po;

                //MENAMPILKAN DATA
                const div = document.getElementById("form_input_barang");
                const newDiv = document.createElement("div");
                newDiv.setAttribute("id","barang_input_detail");
                div.append(newDiv);
                $("#modalDetail").modal('show');
                $('#bulan_detail option[value="'+data.bulan+'"]').prop('selected', true);
                $("#no_po_detail").val(data.no_po);
                $("#divisi_detail").val(data.divisi);
                $("#tanggal_detail").val(data.tanggal);
                $("#batas_waktu_detail").val(data.batas_waktu);
                $('#barang_input_detail').append(s.barang_po);
                $("#total_semua_barang_detail").val(s.total_barang);
                $("#id_detail").val(data.id);
                $("#status").val(data.status);
                document.getElementById('image_ttd_detail').src = asset + data.ttd_pembuat;

                // STATUS
                if(data.status == 0 && data.status == 5) {
                    $('#barang_input_detail input').prop('disabled', true);
                    document.getElementById('tombol_tambah_barang').style.display = 'none';
                    $("#detail input").prop('disabled',true);
                    $("#bulan_detail").prop('disabled',true);
                    document.getElementById("btn_save_detail").style.display = 'none';
                }else {
                    document.getElementById("btn_save_detail").style.display = 'block';
                    $("#detail input").prop('disabled',false);
                    $('#barang_input_detail input').prop('disabled', false);
                    $("#bulan_detail").prop('disabled',false);
                    document.getElementById('tombol_tambah_barang').style.display = 'block';
                }

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

    $("#detail").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url     : $(this).attr('action'),
            data    : $(this).serialize(),
            type    : "POST",
            beforeSend: function() {
                $("#modalCreate").modal('hide');
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }, success : function(s) {
                console.log(s);
                if(s.status == true) {
                    Swal.fire({
                        icon: 'success',
                        title: s.title,
                        text: s.pesan
                    });
                }else {
                    Swal.fire({
                        icon: 'error',
                        title: s.title,
                        text: s.pesan
                    });
                }
                $("#modalDetail").modal('hide');
            }, error : function (e) {
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
            }
        })
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
</script>
<script>
    var i = 1;
    var total_barang, harga, sub;

    function tambah_barang(tipe) {

        if(tipe == "create"){
            create_div("barang_input");
        }else {
            create_div("barang_input_detail");
        }

    }
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
    function create_div(id){
        i++;
        const div = document.getElementById(id)
        const NewDiv = document.createElement("div");
        NewDiv.className = "row" ;
        NewDiv.setAttribute("id","row_"+i+"")

        const DivLg1 = document.createElement("div");
        DivLg1.className = "col-lg-2";
        const DivLg2 = document.createElement("div");
        DivLg2.className = "col-lg-2";
        const DivLg3 = document.createElement("div");
        DivLg3.className = "col-lg-2";
        const DivLg4 = document.createElement("div");
        DivLg4.className = "col-lg-2";
        const DivLg5 = document.createElement("div");
        DivLg5.className = "col-lg-2";
        const DivLg6 = document.createElement("div");
        DivLg6.className = "col-lg-2";


        const Input1 = document.createElement("input");
        Input1.className = "form-control";
        Input1.setAttribute("type","text")
        Input1.setAttribute("name","nama_barang[]")
        Input1.setAttribute("placeholder","Nama Barang")

        const Input2 = document.createElement("input");
        Input2.className = "form-control";
        Input2.setAttribute("type","text")
        Input2.setAttribute("name","unit[]")
        Input2.setAttribute("placeholder","Unit")
        Input2.setAttribute("id","unit_"+i)

        const Input3 = document.createElement("input");
        Input3.className = "form-control";
        Input3.setAttribute("type","text")
        Input3.setAttribute("name","satuan[]")
        Input3.setAttribute("placeholder","Satuan")

        const Input4 = document.createElement("input");
        Input4.className = "form-control";
        Input4.setAttribute("type","text")
        Input4.setAttribute("name","biaya[]")
        Input4.setAttribute("placeholder","Biaya/Unit")
        Input4.setAttribute("id","biaya_"+i)
        Input4.setAttribute("onkeyup","get_harga_perbarang("+i+")");


        const Input5 = document.createElement("input");
        Input5.className = "form-control";
        Input5.setAttribute("type","text")
        Input5.setAttribute("name","jumlah[]")
        Input5.setAttribute("placeholder","Jumlah Unit")
        Input5.setAttribute("id","jumlah_"+i)
        Input5.setAttribute("readonly"," ")


        const Input6 = document.createElement("input");
        Input6.className = "form-control";
        Input6.setAttribute("type","text")
        Input6.setAttribute("name","keterangan[]")
        Input6.setAttribute("placeholder","Keterangan")

        const br = document.createElement("br");
        br.setAttribute("id","br_"+i+"");

        DivLg1.append(Input1)
        DivLg2.append(Input2)
        DivLg3.append(Input3)
        DivLg4.append(Input4)
        DivLg5.append(Input5)
        DivLg6.append(Input6)
        NewDiv.append(DivLg1);
        NewDiv.append(DivLg2);
        NewDiv.append(DivLg3);
        NewDiv.append(DivLg4);
        NewDiv.append(DivLg5);
        NewDiv.append(DivLg6);
        div.append(NewDiv);
        div.append(br);
        if( id== "barang_input") {
            $("#total_semua_barang").val(i)
        }else {
            $("#total_semua_barang_detail").val(i)
        }

        $("#unit_"+i).attr("onkeyup","get_total_barang("+i+")");

    }
    function get_total_barang(sub) {
        var set_id = "unit_"+sub;
        total_barang = $("#"+set_id).val();
        return calculate(total_barang,harga,sub);

    }
    function get_harga_perbarang(sub) {
        var set_id = "biaya_"+sub;
        harga = $("#"+set_id).val();
        return calculate(total_barang,harga,sub);
    }
    function calculate(total_barang,harga,sub) {
        var calculates = total_barang * harga;
        if(isNaN(calculates)) {
            var val = 0;
        }else {
            var val = calculates;
        }
        $("#jumlah_"+sub).val(val)

    }
    </script>
@endpush
