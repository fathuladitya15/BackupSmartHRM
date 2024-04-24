@extends('vBase')
@section('title') {{ 'Lembur - Pages' }} @endsection

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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lembur /</span>Data Lembur</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Lembur </h5>
            </div>
            <div class="card-title">
                <button class="btn btn-primary" style="float: right;" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Tambah Lembur</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Tugas dikerjakan</th>
                            <th>Mulai Jam</th>
                            <th>Sampai Jam</th>
                            <th>Jumlah Jam</th>
                            <th>Status</th>
                            <th>Disetujui Oleh</th>
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Tambah Lembur</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                <input type="hidden" name="id_lembur" id="id_lembur" value="">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <table class="table ">
                            <tbody>
                                <tr>
                                    <td width="100">  <img src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" alt="" width="90"> </td>
                                    <td style="color: black; text-align:center">PT Proven Force Indonesia</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="color: black; text-align:center">Form Intruksi Lembur</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="tanggal_lembur" id="tanggal_lembur" type="date" value="">

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Nama Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="nama_karyawan" id="nama_karyawan" type="text" value="{{ $data_karyawan->nama_karyawan }}" readonly>
                            <input class="form-control" required="required"  name="id_karyawan" id="id_karyawan" type="hidden" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jabatan" id="jabatan" type="text" value="{{ $jabatan }}"  readonly>

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="divisi" id="divisi" type="text" value="{{ $divisi }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Lokasi Kerja</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="lokasi_kerja" id="lokasi_kerja" type="text" value="{{ $lokasi_kerja }}" readonly >
                            <input class="form-control" required="required" name="id_client" id="id_client" type="hidden" value="" readonly >

                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Alasan Lembur</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="alasan_lembur" id="alasan_lembur" type="text" value="" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jam Mulai</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jam_mulai" id="jam_mulai" type="time"  >
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Jam Selesai</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jam_selesai" id="jam_selesai" type="time"  >

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tugas Yang Dikerjakan</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea name="tugas" id="tugas" cols="1" rows="1" class="form-control" required></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Jumlah Jam</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="jumlah_jam" id="jumlah_jam" type="text" readonly >

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Batch</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" name="batch" id="batch" required="required" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Group</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" name="group" id="group" type="text" >

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <p>
                                Petunjuk pengisian form Jam : <br>
                                *) AM Berlaku mulai dari 00.00 WIB sampai dengan 11:59 WIB. <br>
                                *) PM Berlaku mulai dari 12.00 WIB sampai dengan 23.59 WIB. <br>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="col-form-label" for="basic-default-name">Tanda Tangan</label> <br/>
                            <a href="javascript:void(0)" onclick="ttd('{{ Auth::user()->id_karyawan }}')" class="btn btn-primary btn-sm" id="button_ttd">Tanda Tangani</a>
                            <img class="image_ttd" src="" alt="" id="image_ttd" width="100"> <br  />
                            <div class="" style="">
                                <p id="">( {{ Auth::user()->name }} )</p>
                            </div>
                            <input type="hidden" name="ttd" value="" id="ttd" required >
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    var url_data    = "{{ route('lembur-data',['hash' => HashVariable(Auth::user()->id_karyawan)]) }}";
    var url_save    = "{{ route('lembur-save') }}";
    var url_detail  = "{{ route('lembur-detail') }}";
    var assets      = "{{ asset('') }}";

</script>
<script>
    var table  = $('#myTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: url_data,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },{
            data: 'tanggal_lembur',
            name: 'tanggal_lembur',
            orderable: false,
            searchable: false
        },
        {
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
        },
        {
            data: 'jam_selesai',
            name: 'jam_selesai',
            orderable: false,
            searchable: false
        },
        {
            data: 'total_jam',
            name: 'total_jam',
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
            data: 'disetujui_oleh',
            name: 'disetujui_oleh',
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
</script>
<script src="{{ asset('assets/js/lembur.js') }}"></script>

@endpush
