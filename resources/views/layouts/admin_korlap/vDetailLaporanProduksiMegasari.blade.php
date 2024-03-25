@extends('vBase')
@section('title') {{ 'Detail Laporan Produksi' }} @endsection
@push('css')
<style>
    .black {
        color: black;

    }
    h4 {
        color: white;
    }
    h3 {
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

</style>

@endpush
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Produk /</span><span class="text-muted fw-light">Laporan Produksi /</span>{{ $data->keterangan }}</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Laporan Produksi {{ $data->keterangan }}  </h5>
            </div>
            <br>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        {{-- TABLE PRIMARY --}}
                        <table class="table" id="myTable">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>No</th>
                                    <th>Nomor Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Satuan</th>
                                    @for ($i = 0; $i < $totalDays; $i++)
                                        @php
                                            $currentDate = $startDate->copy()->addDays($i);
                                        @endphp
                                        <th style="text-align: center;">{{ $currentDate->translatedFormat('d-M') }}</th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                    @endfor
                                    <th>Total Produk</th>
                                    <th>Harga Produk/pcs</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                              </thead>
                            <tbody>

                            </tbody>
                            <tfoot style="background-color: #47b4f5; ">
                                <tr style="">
                                    <th colspan="4" style="text-align: center; color:white">Total</th>
                                    @for ($i = 1; $i <= $totalDays; $i++)
                                        <th style="color: white;" id="total_tanggal_{{ $i }}"></th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                    @endfor
                                    <th id="totalProduk" style="color: white;">Total Produk</th>
                                    <th id="totalHargaProduk" colspan="2" style="text-align: right; color: white;">Total Tagihan</th>
                                    <th></th>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive text-nowrap">
                    <hr>
                    <h3 style="color:black">Produk Lainnya</h3>
                    <hr>
                    {{-- TABLE NON PRIMARY --}}
                    <table class="table" id="myTableS" style="width:100%" >
                        <thead>
                            <tr class="text-nowrap">
                                <th>No</th>
                                <th>Nomor Produk</th>
                                <th>Nama Produk</th>
                                <th>Satuan</th>
                                @for ($i = 0; $i < $totalDays; $i++)
                                    @php
                                        $currentDate = $startDate->copy()->addDays($i);
                                    @endphp
                                    <th style="text-align: center;">{{ $currentDate->translatedFormat('d-M') }}</th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                @endfor
                                <th>Total Produk</th>
                                <th>Harga Produk/pcs</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                        <tbody>

                        </tbody>
                        <tfoot style="background-color: #47b4f5;">
                            <tr style="">
                                <th colspan="4" style="text-align: center; color:white">Total</th>
                                @for ($i = 1; $i <= $totalDays; $i++)
                                    <th style="color: white;" id="n_total_tanggal_{{ $i }}"></th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                @endfor
                                <th id="totalProduk_n" style="color: white;">Total Produk</th>
                                <th id="totalHargaProduk_n" colspan="2" style="text-align: right; color: white;">Total Tagihan</th>
                                <th></th>

                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <br>
                        <hr>
                            <h3 style="color:black">Rekapitulasi Laporan </h3>
                        <hr>
                        <table class="table" id="tableRekap">
                            <thead>
                                <tr class="text-nowrap">
                                    @for ($i = 0; $i < $totalDays; $i++)
                                        @php
                                            $currentDate = $startDate->copy()->addDays($i);
                                        @endphp
                                        <th style="text-align: center;">{{ $currentDate->translatedFormat('d-M') }}</th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                    @endfor
                                    <th>Total Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-nowrap">
                                    @foreach ($dataRekap as $item => $value)
                                        <td>{{ $value == null ? "0" : $value }}</td>
                                    @endforeach
                                    <td>{{ $totalProduk->total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (in_array(Auth::user()->roles,['admin','korlap']))
                <div class="row">
                    <br>
                    <hr>
                        <h3 style="color:black">Perhitungan Laporan </h3>
                    <hr>
                    <form action="#" id="kirimLaporan" >
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="fee">Management Fee %</label>
                                <span class="text-danger pl-1">*</span>
                                <input class="form-control" required="required"  name="fee" type="number" value="" id="fee" >
                                <input type="hidden" id="" name="id_list_laporan" value="{{ Request::segment(3)  }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="tagihan">Total Tagihan </label>
                                <input class="form-control" required="required"  name="tagihan" type="text" value="{{ $totalHargaProdukRP }}" id="tagihan" readonly >
                                <input type="hidden" name="mentahan_harga" value="{{ $totalHargaProdukInt }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="hasil"> Hasil </label>
                                <input class="form-control" required="required"  name="hasil" type="text" value="" id="hasil" readonly >
                            </div>
                            <div class="form-group col-md-3" style="margin-top: 8px">
                                <label class="col-form-label" for="hasil">   </label>
                                <button type="submit" class="btn btn-primary form-control"> <i class="menu-icon tf-icons bx bx-chevrons-right"></i>Kirim Laporan</button>
                                {{-- <input class="form-control" required="required"  name="hasil" type="text" value="" id="hasil" readonly > --}}
                            </div>
                        </div>
                    </form>
                </div>
                    {{-- @if (in_array($data->status ,[1,3]) ) --}}
                    {{-- @else
                        <div class="row">
                            <br>
                            <hr>
                                <h3 style="color:black">Perhitungan Laporan </h3>
                            <hr>
                            <form action="#" id="kirimLaporan" >
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label class="col-form-label" for="fee">Management Fee %</label>
                                        <span class="text-danger pl-1">*</span>
                                        <input class="form-control" required="required"  name="fee" type="number" value="{{ $data->persentase }}" id="fee" readonly>
                                        <input type="hidden" id="" name="id_list_laporan" value="{{ Request::segment(3)  }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-form-label" for="tagihan">Total Tagihan </label>
                                        <input class="form-control" required="required"  name="tagihan" type="text" value="{{ $totalHargaProdukRP }}" id="tagihan" readonly >
                                        <input type="hidden" name="mentahan_harga" value="{{ $totalHargaProdukInt }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-form-label" for="hasil"> Hasil Persentase </label>
                                        <input class="form-control" required="required"  name="hasil" type="text" value="{{ 'Rp. '. $data->hasil_persentase }}" id="hasil" readonly >
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-form-label" for="hasil"> Total Semua </label>
                                        <input class="form-control" required="required"  name="hasil" type="text" value="{{ 'Rp. '. number_format($data->total_tagihan,2,',','.') }}" id="hasil" readonly >
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif --}}
                @elseif (Auth::user()->roles =='spv-internal')
                <div class="row">
                    <br>
                    <hr>
                        <h3 style="color:black">Perhitungan Laporan </h3>
                    <hr>
                    <form action="#" id="kirimLaporan" >
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="fee">Management Fee %</label>
                                <span class="text-danger pl-1">*</span>
                                <input class="form-control" required="required"  name="fee" type="number" value="{{ $data->persentase }}" id="fee" readonly>
                                <input type="hidden" id="" name="id_list_laporan" value="{{ Request::segment(3)  }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="tagihan">Jumlah Tagihan </label>
                                <input class="form-control" required="required"  name="tagihan" type="text" value="{{ $totalHargaProdukRP }}" id="tagihan" readonly >
                                <input type="hidden" name="mentahan_harga" value="{{ $totalHargaProdukInt }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="hasil"> Hasil Persentase </label>
                                <input class="form-control" required="required"  name="hasil" type="text" value="{{ 'Rp. '. $data->hasil_persentase }}" id="hasil" readonly >
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label" for="hasil"> Total Tagihan + Management Fee </label>
                                @php
                                    $result         = $data->total_tagihan * ($data->persentase / 100);
                                    $totalF        = $data->total_tagihan + $result;
                                    $toRp           =  "Rp. " .number_format(round($totalF,1),2,',','.');
                                @endphp
                                <input class="form-control" required="required"  name="hasil" type="text" value="{{ $toRp  }}" id="hasil" readonly >
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- modal input produk  --}}
<div class="modal fade" id="modalInput" tabindex="-1" role="dialog" aria-labelledby="modalInputTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="modalInputLongTitle"></h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update_laporan" method="POST" action="{{ route('laporan-produksi-update-detail') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id" value="">
                <input type="hidden" id="table_name" name="table_name" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label" for="total_produk">Total Produk</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="total_produk" type="text" value="" id="total_produk" >
                        </div>
                    </div>
                </div>
                <div id="aksi">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>

    $(document).ready(function () {
        var columns         = "{{ $totalDays }}";
        var url_data        = "{{ route('laporan-produksi-detail-data') }}";
        var id              = "{{ Request::segment(3) }}";
        var url_get_detail  = "{{ route('laporan-produksi-get-detail') }}";


        // Columns Default
        var table_columns = [
            {
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {data:'no_produk',name : 'no_produk'},
            {data:'nama_produk',name : 'nama_produk'},
            {data:'satuan_produk',name : 'satuan_produk'},
        ];

        // Loping berdasarkan tanggal
        for (let i = 1; i <= columns; i++) {
            table_columns.push({
                data: 'tanggal_' + i,
                name :'tanggal_'+i,
                className : "tanggal_"+i,
                render :  function(data, type, row) {
                    return  formatRupiah('' + data, 'IDR');
                }
            },);

        }

        // Tambahan Columns
        table_columns.push(
            {
                data: 'total_produk',
                name : 'total_produk',
                render :  function(data, type, row) {
                    return  formatRupiah('' + data, 'IDR');
                }
            },
            {data: 'harga_produk_satuan',name : 'harga_produk_satuan'},
            {data: 'total_harga_produk',name : 'total_harga_produk'},
            {data: 'aksi',name : 'aksi'},
        );


        // TABLE PRIMARY
        var table       = $('#myTable').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : url_data,
                type : "POST",
                data: {id : id ,tipe_produk : 'primary'},
            },
            columns: table_columns,
            drawCallback: function(settings) {
                var api = this.api();
                api.rows().every(function() {
                    var data = this.data();
                    var node = this.node();
                    $(node).attr('data-id', data.id); // Menambahkan atribut data-id
                });
            },
            footerCallback:function() {
                $.ajax({
                    url: "{{ route('lap-produk-totals') }}",
                    type: "POST",
                    data: {id : id, tipe_produk: 'primary'},
                    dataType: "json",
                    success: function(s) {
                        var response = s.totals;
                        for (let index = 0; index < response.length; index++) {
                            const element = response[index];
                            $.each(element, function(index, value) {
                                $('#'+index).text(value);
                            });
                        }
                        $('#totalProduk').text(s.totalProduk);
                        $('#totalHargaProduk').text(s.totalHargaProduk)
                    }
                });
            }
        });

        // TABLE NON PRIMARY
        var table_2     = $("#myTableS").dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : url_data,
                type : "POST",
                data: {id : id ,tipe_produk : 'n_primary'},
            },
            columns: table_columns,
            drawCallback: function(settings) {
                var api = this.api();
                api.rows().every(function() {
                    var data = this.data();
                    var node = this.node();
                    $(node).attr('data-id', data.id); // Menambahkan atribut data-id
                });
            },
            footerCallback:function() {
                $.ajax({
                    url: "{{ route('lap-produk-totals') }}",
                    type: "POST",
                    data: {id : id, tipe_produk : 'n_primary'},
                    dataType: "json",
                    success: function(s) {
                        var response = s.totals;
                        for (let index = 0; index < response.length; index++) {
                            const element = response[index];
                            $.each(element, function(index, value) {
                                $('#n_'+index).text(value);
                            });
                        }
                        $('#totalProduk_n').text(s.totalProduk);
                        $('#totalHargaProduk_n').text(s.totalHargaProduk)
                    }
                });
            }
        })

        // TABLE REKAP
        var tableRekap  = $("#tableRekap").dataTable();



        // CLICK TABLE PRIMARY
        $('#myTable tbody').on('click', 'td', function() {
            var id = $(this).closest('tr').data('id');
            var className = $(this).attr('class');
            var newText = className.replace('_', ' '); // Mengganti underscore dengan spasi
            $.ajax({
                url: url_get_detail,
                data: {id : id,table: className},
                type: 'POST',
                success : function(ss) {
                    var s = ss.data;
                    document.getElementById('modalInputLongTitle').innerHTML = 'Total Produk ' + newText;
                    $("#modalInput").modal('show');
                    $("#id").val(id);
                    $("#table_name").val(className);
                    $("#total_produk").val(ss.name);
                },error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
        // CLICK TABLE NON PRIMARY

        $('#myTableS tbody').on('click', 'td', function() {
            var id = $(this).closest('tr').data('id');
            var className = $(this).attr('class');
            var newText = className.replace('_', ' '); // Mengganti underscore dengan spasi
            $.ajax({
                url: url_get_detail,
                data: {id : id,table: className},
                type: 'POST',
                success : function(ss) {
                    var s = ss.data;
                    document.getElementById('modalInputLongTitle').innerHTML = 'Total Produk ' + newText;
                    $("#modalInput").modal('show');
                    $("#id").val(id);
                    $("#table_name").val(className);
                    $("#total_produk").val(ss.name);
                },error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });


        var totalHargaProdukInt = "{{ $totalHargaProdukInt }}";

        $("#update_laporan").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url : $(this).attr('action'),
                data : $(this).serialize(),
                type : "POST",
                beforeSend :  function() {
                    Swal.fire({
                        title: 'Loading...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                },
                success: function(s) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });
                    $("#modalInput").modal('hide');
                    $("#update_laporan").trigger('reset');
                    $("#tagihan").val(s.hargaTotal);
                    totalHargaProdukInt = s.totalHargaProdukInt
                },error: function(e) {
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
                    table_2.DataTable().ajax.reload();
                    // Swal.close();

                }

            })
        });

        var url_calculate = "{{ route('lap-produk-calculate') }}";

        $("#fee").on('input', function () {
            var harga  =  totalHargaProdukInt;
            var fee = parseFloat($('#fee').val());


            $.ajax({
                url : url_calculate,
                data: {harga :  harga, fee : fee },
                type: "POST",
                success: function(s) {
                    // console.log(s);
                    $('#hasil').val(s.rp);
                },error : function(e) {
                    // console.log(e);
                }
            })

        });

    });

    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split    = number_string.split(','),
        sisa     = split[0].length % 3,
        rupiah     = split[0].substr(0, sisa),
        ribuan     = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ?  rupiah : '0');
    }

    $("#kirimLaporan").submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah data yang anda kirim kan sudah sesuai ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Kirim !"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            url : "{{ route('lap-produk-kirim') }}",
                            type: 'POST',
                            data: $(this).serialize(),
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Loading...',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                            },success : function(s) {
                                console.log(s);
                                Swal.fire({
                                    title: s.title,
                                    text: s.pesan,
                                    icon: "success",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                                setTimeout(function() {
                                    window.location.href = s.link; // Ganti dengan halaman yang diinginkan
                                }, 2000);
                            }, error : function(e) {
                                console.log(e);
                                Swal.fire({
                                    title: "Terjadi kesalahan",
                                    text: "Hubungi tim IT",
                                    icon: "error"
                                });
                            },complete: function() {


                            }
                        })
                }
            });
        })

</script>

@endpush
