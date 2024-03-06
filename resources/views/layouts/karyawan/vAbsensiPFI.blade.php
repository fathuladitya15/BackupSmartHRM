@extends('vBase')
@section('title') {{ 'Form Absensi' }}@endsection

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
</style>

@endpush
@section('content')
<h4 class="fw-bold py-3 mb-4">Form Absensi {{ Carbon\Carbon::now()->translatedFormat("l, d F Y") }}</h4>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Absensi</h5>
            </div>
            <div class="card-body">
                <br><br>
                <div class="row">
                    <div class="col-lg-6">
                        {{-- <label for="">Tombol Absensi</label> --}}
                        <button class="btn btn-danger">Klik Untuk Absensi</button>
                    </div>
                    <div class="form-group col-lg-6">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Hari/ Tanggal</td>
                                    <td>:</td>
                                    <td><div id="time"></div></td>
                                </tr>
                                <tr>
                                    <td>Kordinat Anda</td>
                                    <td>:</td>
                                    <td>
                                        <div id="koordinat"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pilih Lokasi Absen</td>
                                    <td>:</td>
                                    <td>
                                        <select name="lokasi_absen" id="" class="form-control">
                                            <option value="">-- Pilih Lokasi Absen --</option>
                                            <option value="">Soho</option>
                                            <option value="">Alamanda</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')

<script>
    var times = "{{ route('times') }}";
    var timeDisplay = document.getElementById("time");


        function refreshTime() {
            $.ajax({
                url : times,
                type : "GET",
                success: function(s) {
                    console.log(s);
                    timeDisplay.innerHTML = s;
                }, error : function(e) {
                    console.log(e)
                }
            });
        }

        setInterval(refreshTime, 1000);
</script>
<script>
    const x = document.getElementById("demo");

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            x.innerHTML = "Latitude: " + position.coords.latitude +
            "<br>Longitude: " + position.coords.longitude;
        }
        setInterval(showPosition(position))
</script>
@endpush
