@extends('vBase')
@section('title') {{  'Data Absensi ' }}@endsection
@push('css')


<style>
        .header-detail{
        align:center;
    }
    .header-detail .logo {
        margin: auto;
        align:center;
        padding-left: 50px;
    }
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
    }
    .logo {
        width: 30%;
    }
</style>
@endpush
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Absensi /</span> Data Absensi Internal {{ $data_karyawan->kategori }} PFI / {{ $data_karyawan->nama_karyawan }} - {{ $data_karyawan->id_karyawan }}</h4>

<br><br>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data  Absensi {{ $data_karyawan->nama_karyawan. '-' .$data_karyawan->id_karyawan }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modalFilter"> <i class='bx bx-filter' ></i> Filter</button>
                    </div>
                    <div class="col-lg-6">
                        <a href="{{ route('absensi-doc-perorang') }}" onclick="event.preventDefault(); document.getElementById('print-form').submit();" class="btn btn-danger "  style="float: right"> <i class='bx bx-printer' ></i> Cetak Dokumen</a>
                        <form id="print-form" action="{{ route('absensi-doc-perorang') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="id_karyawan" value="{{ $data_karyawan->id_karyawan }}">
                            <input type="hidden" name="from_date" value="{{ $from_date }}">
                            <input type="hidden" name="to_date" value="{{ $to_date }}">
                        </form>
                    </div>

                </div>
            </div>
            <br>
            <div class="card-body">
                <div class="header-detail">
                    <div class="logo" style="text-align: center">
                        <img class="absensi" src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" style="width: 50%" alt="">
                    </div>
                    <br>
                    <h4 style="color: black; text-align:center;">{{ $data_karyawan->nama_karyawan }}</h4>
                    <h5 style="color: black; text-align:center;">{{ $data_karyawan->id_karyawan }}</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    @foreach ($data as $tahun => $Databulan )
                    <table class="table" id="myTable">
                        @foreach ($Databulan as $bulan => $DataHari)
                            <thead>
                                <tr>
                                    <th style="text-align:center;" colspan="13"> <b>Riwayat Kehadiran Bulan {{ $bulan }} {{ $tahun }}</b></th>
                                </tr>
                                <tr class="text-nowrap">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jabatan</th>
                                    <th>Divisi</th>
                                    <th>Absen Masuk</th>
                                    <th>Lokasi Absen </th>
                                    <th>Detail Lokasi</th>
                                    <th>Absen Pulang</th>
                                    <th>Lokasi Absen</th>
                                    <th>Detail Lokasi</th>
                                    <th>shift</th>
                                    <th>Catatan</th>
                                    @if (Auth::user()->roles == 'hrd')
                                        <th>Keterlambatan</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($DataHari as $hari => $value )
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        {{-- <td>{{ \Carbon\Carbon::parse($dt->tanggal)->translatedFormat('l, d F Y') }}</td> --}}
                                        <td>{{ $hari }}</td>
                                        <td>{{ $value[0]['jabatan'] }}</td>
                                        <td>{{ $value[0]['divisi'] }}</td>
                                        <td>{{ $value[0]['jam_masuk'] }}</td>
                                        <td>{{ $value[0]['lokasi_absen_masuk'] }}</td>
                                        <td>{{ $value[0]['detail_lokasi_absen_masuk'] }}</td>
                                        <td>{{ $value[0]['jam_keluar'] }}</td>
                                        <td>{{ $value[0]['lokasi_absen_pulang'] }}</td>
                                        <td>{{ $value[0]['detail_lokasi_absen_masuk'] }}</td>
                                        <td>
                                            {{ $value[0]['shift'] == null ? 'Non Shift' :  $value[0]['shift'] }}
                                        </td>
                                        <td>{{ $value[0]['catatan']  }}</td>
                                        @if (Auth::user()->roles == 'hrd')
                                            <td>
                                                @php
                                                    $kr = request()->segment(4);
                                                    $jamMasukKaryawan = $value[0]['tanggal']." ".$value[0]['jam_masuk'];

                                                    if($kr == 'pusat'){
                                                        $waktuMaksimalAbsen = $value[0]['tanggal']." 09:00:00";
                                                    }else {
                                                        $waktuMaksimalAbsen = $value[0]['tanggal']." 08:00:00";
                                                    }

                                                    $absensiCarbon = Carbon\Carbon::parse($jamMasukKaryawan);

                                                    $waktuMaksimalAbsenCarbon = Carbon\Carbon::parse($waktuMaksimalAbsen);

                                                    $selisih = $absensiCarbon->diffInMinutes($waktuMaksimalAbsenCarbon);

                                                    $cekGT =  $absensiCarbon->gt($waktuMaksimalAbsenCarbon);
                                                    if($cekGT == true) {
                                                        $selisih        = $absensiCarbon->diffInMinutes($waktuMaksimalAbsenCarbon);
                                                        // Konversi selisih waktu ke dalam format jam dan menit
                                                        $jam        = floor($selisih / 60);
                                                        $menit      = $selisih % 60;
                                                        if($jam == 0) {
                                                            $r = $menit.' menit';
                                                        }else if($menit == 0) {
                                                            $r = $jam.' jam';
                                                        }else {
                                                            $r = $jam.' jam '.$menit.' menit';
                                                        }

                                                    }else {
                                                        $r = "";
                                                    }
                                                @endphp
                                                {{ $r }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </table>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Filter Data</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data" method="GET" action="{{ route('absensi-search-one',['karyawan' => Request::segment(2),'id' => $j]) }}">
                @csrf
                <input type="hidden" value="{{ $j }}" name="kr">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="" class="col-form-label">Pilih Karyawan</label>
                            <select name="id_karyawan" id="id_karyawan" class="form-control" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($karyawan as $kr )
                                    <option value="{{ $kr->id_karyawan }}">{{ $kr->id_karyawan }} - {{  $kr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="" class="col-form-label">Dari</label>
                            <input type="date" class="form-control" name="from_date" value="" required>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="" class="col-form-label">Sampai</label>
                            <input type="date" class="form-control" name="to_date" value="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

