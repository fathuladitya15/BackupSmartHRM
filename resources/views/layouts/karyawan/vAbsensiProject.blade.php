@extends('vBase')
@section('title') {{  'Data Absensi Anda' }} @endsection

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
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Absensi /</span> Data Absensi   </h4>
<br><br>
@if ($errors->any())
@foreach ($errors->all() as $item)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $item }}
        <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endforeach
@endif
@if($AbsensiHarini == 0)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Anda Belum melakukan absensi hari ini
    <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@else
@endif
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-body">
                <form id="checkin">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="pilih_lokasi" class="form-label">Plih Lokasi</label>
                            <select name="lokasi" id="lokasi" class="form-control" required>
                                <option value="">-- Pilih Lokasi -- </option>
                                <option value="{{ $lokasi_absensi->id }}" >{{ $lokasi_absensi->nama_client }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="pilih_lokasi" class="form-label">Plih Shift</label>
                            <select name="lokasi" id="lokasi" class="form-control">
                                <option value="">-- Pilih Shift -- </option>
                                @if ($cek_shift > 0)
                                    @foreach ($shift as $st)
                                        <option value="{{ $st->id }}">{{ $st->type }}-{{ $st->ke }}</option>

                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="pilih_lokasi" class="form-label">Tanggal</label>
                            <input type="date"  class="form-control" value="{{ date("Y-m-d") }}" name="tanggal" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="pilih_lokasi" class="form-label">Jam</label>
                            <input type="text"  class="form-control" name="jam_masuk" readonly id="jam">
                        </div>
                        <div class="col-md-2">
                            <label for="pilih_lokasi" class="form-label"></label>
                            <input type="submit"  class="btn btn-primary form-control" name="submit" value="Absen">
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data  Absensi </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modalFilter"> <i class='bx bx-filter' ></i> Filter</button>
                    </div>

                </div>
            </div>
            <br>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="myTable"   >
                        <thead>
                            @if (Auth::user()->roles == 'karyawan')
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Absen Masuk</th>
                                    <th>Lokasi Absen Masuk</th>
                                    <th>Detail Lokasi Absen</th>
                                    <th>Mulai Istirahat</th>
                                    <th>Selesai Istirahat</th>
                                    <th>Absen Pulang</th>
                                    <th>lokasi Absen Pulang</th>
                                    <th>Detail Lokasi Absen Pulang</th>
                                    <th>Shift</th>
                                    <th>Catatan</th>
                                </tr>
                            @else
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Absen Masuk</th>
                                <th>Lokasi Absen Masuk</th>
                                <th>Detail Lokasi Absen</th>
                                <th>Absen Pulang</th>
                                <th>lokasi Absen Pulang</th>
                                <th>Detail Lokasi Absen Pulang</th>
                                <th>Shift</th>
                                <th>Catatan</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
            <form id="Add" enctype="multipart/form-data" method="GET" action="#">
                @csrf
                <div class="modal-body">
                    <div class="row">
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

<div class="modal fade" id="modalDownload" tabindex="-1" role="dialog" aria-labelledby="modalDownloadTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title black" id="exampleModalLongTitle">Download Data</h5>
                <button type="button" class="btn btn-default close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Add" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="" class="col-form-label">Dari</label>
                            <input type="date" class="form-control" name="from_date" value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="" class="col-form-label">Sampai</label>
                            <input type="date" class="form-control" name="to_date" value="">
                        </div>

                    </div>
                </div>
                <div class="modal-footer" id="aksi">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Download</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
       var url_data = "{{ route('absensi-data-karyawan') }}";
       var url_cekin = "{{ route('absensi-cekin') }}";
       var roles = "{{ Auth::user()->roles }}";
    </script>
    <script src="{{ asset('assets/js/karyawan/absensi.js') }}"></script>
    <script>
        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();

            // Tambahkan 0 di depan angka jika angka kurang dari 10
            hours = ('0' + hours).slice(-2);
            minutes = ('0' + minutes).slice(-2);
            seconds = ('0' + seconds).slice(-2);

            var currentTime = hours + ":" + minutes + ":" + seconds;
            document.getElementById('jam').value = currentTime;
        }

        // Panggil fungsi updateClock setiap detik
        setInterval(updateClock, 1000);

        $("#checkin").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url : url_cekin,
                data: $(this).serialize(),
                type: "POST",
                beforeSend: function() {

                },success: function(s) {
                    console.log(s);
                }, error : function(e) {
                    console.log(e);
                }, complete: function() {

                }
            })
        })
        </script>
@endpush
