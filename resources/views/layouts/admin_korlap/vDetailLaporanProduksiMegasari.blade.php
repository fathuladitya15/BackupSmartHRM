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
            {{-- <div class="card-title">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah" style="float: right">
                    <i class='bx bx-plus'></i> Tambah Produk
                </button>
            </div> --}}
            <br>
            <div class="card-body">
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
                                    {{-- <th>{{ $currentDate->toDateString() }}</th> --}}
                                    <th>{{ $currentDate->translatedFormat('d-M') }}</th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                @endfor
                                <th>Total Produk</th>
                                <th>Harga Produk/pcs</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" style="text-align: right;">Total</th>
                                @for ($i = 1; $i <= $totalDays; $i++)
                                    {{-- <th>{{ $currentDate->toDateString() }}</th> --}}
                                    <th id="total_tanggal_{{ $i }}"></th> {{-- Menampilkan hari dalam teks (nama hari) --}}
                                @endfor
                                <th id="totalProduk">Total Produk</th>
                                <th id="totalHargaProduk" colspan="2" style="text-align: right;">Total Tagihan</th>
                                <th></th>

                                <!-- Tambahkan kolom lain jika diperlukan -->
                            </tr>
                        </tfoot>
                      </table>
                      <br><br>
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
        var columns = "{{ $totalDays }}";
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

        var url_data    = "{{ route('laporan-produksi-detail-data') }}";
        var id          = "{{ Request::segment(3) }}";

        var table       = $('#myTable').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : url_data,
                type : "POST",
                data: {id : id},
            },
            columns: table_columns,
            drawCallback: function(settings) {
                var api = this.api();
                api.rows().every(function() {
                    var data = this.data();
                    var node = this.node();
                    $(node).attr('data-id', data.id); // Menambahkan atribut data-id
                });
            },footerCallback:function() {
                $.ajax({
                    url: "{{ route('lap-produk-totals') }}",
                    type: "POST",
                    data: {id : id},
                    dataType: "json",
                    success: function(s) {
                        var response = s.totals;
                        for (let index = 0; index < response.length; index++) {
                            const element = response[index];
                            $.each(element, function(index, value) {
                                // console.log("Elemen array1 ke-" + index + ": " + value);
                                $('#'+index).text(value);
                            });
                        }
                        $('#totalProduk').text(s.totalProduk);
                        $('#totalHargaProduk').text(s.totalHargaProduk);
                    }
                });
            }
        });



        var url_get_detail = "{{ route('laporan-produksi-get-detail') }}";
        $('#myTable tbody').on('click', 'td', function() {
            var id = $(this).closest('tr').data('id');
            var className = $(this).attr('class');
            var newText = className.replace('_', ' '); // Mengganti underscore dengan spasi
            $.ajax({
                url: url_get_detail,
                data: {id : id,table: className},
                type: 'POST',
                success : function(ss) {
                    // console.log(ss);
                    var s = ss.data;
                    document.getElementById('modalInputLongTitle').innerHTML = 'Total Produk ' + newText;
                    $("#modalInput").modal('show');
                    $("#id").val(id);
                    $("#table_name").val(className);
                    $("#total_produk").val(ss.name);
                },error: function(xhr, status, error) {
                    // console.log(e);
                    console.error('Error:', error);
                }
            });
        });

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
                    // console.log(s);
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });
                    $("#modalInput").modal('hide');
                    $("#update_laporan").trigger('reset');
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
                    // Swal.close();

                }

            })
        })

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

</script>

@endpush
