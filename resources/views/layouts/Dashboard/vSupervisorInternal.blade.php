@extends('vBase')

@section('title') {{ 'Dashboard' }} @endsection

@section('content')
<!-- NOTIFIKASI INFORMASI -->
@if (in_array(Auth::user()->roles ,['admin','kr-project','kr-pusat',]))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-primary" role="alert">
                Anda Login Sebagai {{ Auth::user()->roles == 'admin' ? "Admin" : "Karyawan" }}
            </div>
        </div>
    </div>
@endif
<!-- /NOTIFIKASI INFORMASI -->


<!-- FIRST COLUMN -->

<div class="row">
    <!-- CARD GREETING -->
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                    <h5 class="card-title text-primary"> {{ salam() }}, {{ Str::upper(Auth::user()->name) }}! 🎉</h5>
                    <p class="mb-4">
                        Apakah sudah absensi hari ini ? Cek Absensi Kehadiran <br>
                        @php
                            $nama_hari = Carbon\Carbon::now()->translatedFormat('l');

                            if($nama_hari == 'Sabtu' || $nama_hari == 'Minggu' ){
                                $status = 'Selamat Beristirahat';
                            }else {
                                $status = 'Selamat Beraktivitas.       '. Carbon\Carbon::now()->translatedFormat('l, m F Y') ;
                            }

                        @endphp
                        {{ $status }}
                    </p>
                    <a href="{{ route('absensi') }}" class="btn btn-sm btn-outline-primary">Lihat Absensi  </a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END GREETING CARD -->

    <!-- INFORMASI -->
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <!-- INFORMASI TOTAL KARYAWAN PROJECT & INTERNAL -->
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset("assets/img/icons/unicons/icons-user-76.png") }}" alt="Credit Card" class="rounded" />
                            </div>
                            <h5  style="color: white; margin-left:30px;">Total Karyawan Internal Pusat - Project</h5>
                        </div>
                        <div >
                            <h1 style="color: white; display: inline-block; width: auto;float: left;">{{ $kr_internal }} </h1>
                            <p style="color: white;display: inline-block; width: auto; float: left;margin-left: 10px;">Karyawan</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset("assets/img/icons/unicons/icons-user-76.png") }}" alt="Credit Card" class="rounded" />
                            </div>
                            <h5  style="color: white; margin-right:110px;">Total Karyawan  Project </h5><br>
                        </div>
                        <div  >
                            <h1 style="color: white; display: inline-block; width: auto;float: left;">{{ $kr_project }} </h1>
                            <p style="color: white;display: inline-block; width: auto; float: left;margin-left: 10px;">Karyawan</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END INFORMASI TOTAL KARYAWAN PROJECT & INTERNAL -->
        <!-- REQUEST APPROVAL -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 mb-4">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset("assets/img/icons/unicons/icons-sign-76.png") }}" alt="chart success" class="rounded"/>
                            </div>
                            <h5  style="color: white; margin-right:552px;">
                                Permintaan untuk persetujuan
                            </h5>
                        </div>
                        <div style="padding-top:16px;" >
                            <h1 style="color: white; display: inline-block; width: auto;float: left;">0 </h1>
                            <p style="color: white;display: inline-block; width: auto; float: left;margin-left: 10px;">Permintaan Persetujuan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Total Revenue -->

    <!--  Pengumuman  -->
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
        <div class="row">
            <div class="card" >
                <div class="card-title mb-0">
                    <h5 class="card-header m-0 me-2 pb-3" style="margin-bottom: 20px; ">Pengumuman</h5>
                </div>
                <div class="card-body" style="padding-top: 18px;">
                    <div class="row">
                        <ul class="p-0 m-0">
                            @if ($pengumuman)
                                @foreach ($pengumuman as $item)
                                    <li class="d-flex mb-4 pb-1">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <img src="{{ asset('assets/img/illustrations/toa.png') }}" alt="User" class="rounded" />
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <small class="text-muted d-block mb-1">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i' ) }}</small>
                                                <h6 class="mb-0">{{ $item->judul }} </h6>
                                            </div>
                                            <div class="user-progress d-flex align-items-center gap-2">
                                                <a href="{{ route('pengumuman-details',['var' => HashVariable($item->id)]) }}"><small class="mb-0">Selengkapnya... </small></a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <small><a href="#">Lihat Semua Pengumuman</a></small>
                            @else
                                 <small>Tidak ada pengumuman</small>
                            @endif
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="row">
            <div class="card">
                <di class="row row-bordered g-0">
                    <h5 class="card-header m-0 me-2 pb-3">Aktivitas</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="table_riwayat">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aktivitas</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($riwayat as $t )
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $t->aktivitas }}</td>
                                            <td>{{ $t->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </di>
            </div>
        </div>
    </div>
</div>
<button onclick="startFCM()">Allow Notification</button>

<!-- END FIRST COLUMN -->
@endsection
@push('js')

<script>
    var table = $("#table_riwayat").dataTable();
    function startFCM() {
        messaging.requestPermission().then(function () {
                return messaging.getToken()
            })
            .then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("store.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token stored.');
                    },
                    error: function (error) {
                        alert(error);
                    },
                });
            }).catch(function (error) {
                alert(error);
            });
    }
</script>

@endpush
