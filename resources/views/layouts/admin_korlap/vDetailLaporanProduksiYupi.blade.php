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
    /* Gaya untuk loading */
    .loading {
        width: 20px;
        height: 20px;
        border: 3px solid #ccc;
        border-top-color: #333;
        border-radius: 50%;
        animation: spin 1s infinite linear;
    }

    .loading-cell {
        text-align: center;
    }

</style>

@endpush
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Produk /</span><span class="text-muted fw-light">Laporan Produksi /</span>{{ $data->keterangan }}</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Laporan Produksi {{ $data->keterangan }} ( {{ $data->from_date }} - {{ $data->to_date }})  </h5>
            </div>
            <div class="card-body">
                <h3 style="color:black">Table Produk {{ $data->keterangan }}</h3>
                <hr>
                <div class="row">
                    <div class="table-responsive text-nowrap">
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
                <hr>
                <h3 style="color:black">Perhitungan Selisih</h3>
                <hr>
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="table_selisih">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Total Produk</th>
                                    <th>input total produk</th>
                                    <th>selisih</th>
                                    <th>status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <hr>
                <h3 style="color:black">Tonase</h3>
                <hr>
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="table_tonase">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tonase</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <br><br>
                <div class="row" style="float: right">
                    <form id="kirimLaporan">
                        <input type="hidden" value="{{ $data->id }}" name="id_list_laporan">
                    @if (Auth::user()->roles == 'spv-internal')
                        <input type="hidden" value="2" name="status">
                        <a href="javascript:void(0)" onclick="acc({{ $data->id }})" id="acc" class="btn btn-success"> <i class="menu-icon tf-icons bx bx-check-circle"></i>Setujui</a>
                        <a href="javascript:void(0)" onclick="reject({{ $data->id }})" id="reject" class="btn btn-danger"> <i class="menu-icon tf-icons bx bxs-x-circle"></i>Tolak</a>
                    @else
                        <input type="hidden" value="1" name="status">
                        <button type="submit" class="btn btn-primary form-control"> <i class="menu-icon tf-icons bx bx-chevrons-right"></i>Kirim Laporan</button>

                    @endif
                    </form>
                </div>
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
                            <input class="form-control" required="required" placeholder="Masukan Total Produk "  name="total_produk" type="text" value="" id="total_produk" >
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
        var columns         = "{{ $totalDays }}";
        var url_data        = "{{ route('laporan-produksi-detail-data') }}";
        var id              = "{{ Request::segment(3) }}";
        var url_get_detail  = "{{ route('laporan-produksi-get-detail') }}";
        var tipe_produk     = "{{ $data->keterangan }}";
        var url_data_yupi   = "{{ route('laporan-produksi-yp-data',['id'=> Request::segment(3),'tipe_produk' => $data->keterangan]) }}";
        var url_selisih     = "{{ route('laporan-produksi-yp-cek-selisih') }}";
        var url_compare     = "{{ route('laporan-produksi-yp-compare',['id'=> Request::segment(3),'tipe_produk' => $data->keterangan]) }}";
        var roles           = "{{ Auth::user()->roles }}";
        var url_back        = "{{ route('laporan-produksi-yp',['kategori' => $data->keterangan]) }}";


        // COLUMN DEFAULT
        var table_columns = [
            {
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {data:'no_produk',name : 'no_produk',},
            {data:'nama_produk',name : 'nama_produk',},
            {data:'satuan_produk',name : 'satuan_produk', orderable: false,searchable: false,},
        ];

        // LOOPING COLUMN BERDASARKAN TOTAL HARI
        for (let i = 1; i <= columns; i++) {
            table_columns.push({
                data: 'tanggal_' + i,
                name :'tanggal_'+i,
                className : "tanggal_"+i,
                orderable: false,
                searchable: false,
                render :  function(data, type, row) {
                    return  data == null ? 0 : data ;
                }
            },);

        }

        // TAMBAHAN COLUMN
        table_columns.push(
            {
                data: 'total_produk',
                name : 'total_produk',
                orderable: false,
                searchable: false,
                // render :  function(data, type, row) {
                //     return  formatRupiah('' + data, 'IDR');
                // }
            },
            {data: 'harga_produk_satuan',name : 'harga_produk_satuan', orderable: false,
                searchable: false,},
            {data: 'total_harga_produk',name : 'total_harga_produk', orderable: false,
                searchable: false,},
            {data: 'aksi',name : 'aksi', orderable: false,
                searchable: false,},
        );

        // TABLE PRODUK
        var table   = $('#myTable').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : url_data,
                type : "POST",
                data: {id : id ,tipe_produk : tipe_produk},
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
                    data: {id : id, tipe_produk: tipe_produk, hari : columns},
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

        // CLICK INPUT TOTAL PRODUK
        $('#myTable tbody').on('click', 'td', function() {
            var id = $(this).closest('tr').data('id');
            var className = $(this).attr('class');
            var newText = className.replace('_', ' '); // Mengganti underscore dengan spasi
            $.ajax({
                url: url_get_detail,
                data: {id : id,table: className},
                type: 'POST',
                beforeSend:function() {

                },
                success : function(ss) {
                    console.log(ss);
                    var s = ss.data;
                    document.getElementById('modalInputLongTitle').innerHTML = 'Produk '+ s.nama_produk + newText;
                    $("#modalInput").modal('show');
                    $("#id").val(id);
                    $("#table_name").val(className);
                    $("#total_produk").val(ss.name);
                },error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // UPDATE TOTAL PRODUK PERTANGGAL
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
                    if(s.status == true) {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: "success"
                        });
                        $("#modalInput").modal('hide');
                        $("#update_laporan").trigger('reset');
                        $("#tagihan").val(s.hargaTotal);
                        totalHargaProdukInt = s.totalHargaProdukInt

                    }else {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: 'warning',
                        });
                    }

                },error: function(e) {
                    var errors = '';
                    $.each(e.responseJSON.errors, function(key, value) {
                        errors += value + '<br>'; // Membuat daftar pesan kesalahan
                    });
                    Swal.fire({
                        icon: 'warning',
                        title: 'Terjadi Kesalahan!',
                        html: errors
                    });
                },complete: function() {
                    table.DataTable().ajax.reload();
                    table_selisih.DataTable().ajax.reload();
                    table_tonase.DataTable().ajax.reload();


                }

            })
        });


        var table_selisih = $("#table_selisih").dataTable({
            processing: true,
            serverSide: true,
            ajax: url_data_yupi,
            searching: false,
            columns: [
                {data: 'tanggal',name:'tanggal', orderable: false,
                searchable: false,},
                {data:'total_produk',name : 'total_produk', orderable: false,
                searchable: false,},
                {data:'input',name : 'input', orderable: false,
                searchable: false,},
                {data:'selisih',name : 'selisih', orderable: false,
                searchable: false,},
                {data:'status',name : 'status', orderable: false,
                searchable: false,},
            ]
        });

        var table_tonase = $("#table_tonase").dataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: url_compare,
            columns: [
                {data: 'name',name:'name', orderable: false,
                searchable: false,},
                {data:'total',name : 'total', orderable: false,
                searchable: false,},
                {data:'satuan',name : 'satuan', orderable: false,
                searchable: false,},
            ]
        })


        function cek_selisih(no) {
            var input = $("#input_selisih").val();
            var total = $("#total_produk").val();
            var tanggal = $("#tanggal").val();
            var id_table_lap_period = $("#id_table_lap_period").val();
            var form = $("#formSelisih_"+no).serialize();
            $.ajax({
                url : url_selisih,
                data : form,
                type: "POST",
                beforeSend:function(){
                    Swal.fire({
                        title: 'Dalam proses',
                        html: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                }, success: function(s) {
                    console.log(s);
                    if(s.status == false) {
                        Swal.fire({
                            icon: 'warning',
                            title: s.title,
                            html: s.pesan
                        });
                    }else {
                        swal.close()
                    }
                    $("#input_selisih").val(s.TotalCountProduk_M);
                }, error: function (e) {
                    console.log(e);
                    var errors = '';
                        $.each(e.responseJSON.errors, function(key, value) {
                            errors += value + '<br>'; // Membuat daftar pesan kesalahan
                        });
                        Swal.fire({
                            icon: 'warning',
                            title: 'Input tidak valid!',
                            html: errors
                        });
                }, complete: function() {
                    table_selisih.DataTable().ajax.reload();
                    table_tonase.DataTable().ajax.reload();
                }
            });
        }

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

        var url_acc = "{{ route('laporan-produksi-update') }}";
        function acc(id) {
            const button        = document.getElementById('acc');
            const stoploading   = '<i class="menu-icon tf-icons bx bx-check-circle"></i>';
            const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';

            $.ajax({
                url : url_acc,
                data: {id:id,status : 2},
                type: "POST",
                beforeSend: function() {
                    // button.innerHTML = loading;
                    // button.disabled = true;
                },success: function(s) {
                    console.log(s);
                    if(s.status == true) {
                        Swal.fire({
                            icon : 'success',
                            title: s.title,
                            html: s.pesan ,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                    }else {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: "warning"
                        });
                    }
                }, error : function(e) {
                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: 'Hubungi Tim IT',
                            icon: "error"
                        });
                }, complete: function() {
                    // table.dataTable().ajax.reload();
                    // table.DataTable().ajax.reload();

                    setTimeout(function() {
                            window.location.href = url_back; // Ganti dengan halaman yang diinginkan
                        }, 2000);

                }
            })
        }

        function reject(id) {
            const button        = document.getElementById('reject');
            const stoploading   = '<i class="menu-icon tf-icons bx bx-check-circle"></i>';
            const loading       = '<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> Loading';

            $.ajax({
                url : url_acc,
                data: {id:id,status : 3},
                type: "POST",
                beforeSend: function() {
                    // button.innerHTML = loading;
                    // button.disabled = true;
                },success: function(s) {
                    console.log(s);
                    if(s.status == true) {
                        Swal.fire({
                            icon : 'success',
                            title: s.title,
                            html: s.pesan ,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                    }else {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: "warning"
                        });
                    }
                }, error : function(e) {
                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: 'Hubungi Tim IT',
                            icon: "error"
                        });
                }, complete: function() {
                    // table.dataTable().ajax.reload();
                    // table.DataTable().ajax.reload();

                    setTimeout(function() {
                            window.location.href = url_back; // Ganti dengan halaman yang diinginkan
                        }, 2000);

                }
            })
        }


</script>

@endpush
