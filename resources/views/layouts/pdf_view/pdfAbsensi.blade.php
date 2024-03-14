
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $filename }}</title>

    <style>
            table{
                border-collapse: collapse;
            }

            .header-detail{
                align:center;
            }
            .header-detail .logo {
                margin: auto;
                align:center;
                padding-left: 120px;
            }
            .logo {
                width: 30%;
            }
            .absensi{
                border-radius: 4px;
                padding: 5px;
                width: 150px;
            }
            .header-detail h4 {
                color: black;
                text-align: center;
            }
            .header-detail h5 {
                color: black;
                text-align: center;
            }
    </style>
</head>
<body>
    <div class="header-detail">
        <div class="logo">
            <img class="absensi" src="{{ public_path('assets/img/layouts/logo-pfi-dark.png') }}" alt="">
        </div>
        <br>
        <h4>{{ Str::upper($data_kr->nama_karyawan) }}</h4>
        <h5>{{ Str::upper($data_kr->id_karyawan) }}</h5>
    </div>
    @foreach ($data as $tahun => $Databulan )
    <table class="table table-striped" border="1" width="100%">
        @foreach ($Databulan as $bulan => $DataHari)
            <thead>
                <tr style="font-size: 60%;">
                    <th style="text-align:center;" colspan="12"> <b>Riwayat Kehadiran Bulan {{ $bulan }} {{ $tahun }}</b></th>
                </tr>
                <tr class="text-nowrap" style="font-size: 50%;">
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
                    <th>catatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($DataHari as $hari => $value )
                    <tr style="font-size: 60%;">
                        <td>{{ $no++ }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($dt->tanggal)->translatedFormat('l, d F Y') }}</td> --}}
                        <td>{{ $hari }}</td>
                        <td>{{ $value[0]['jabatan'] }}</td>
                        <td>{{ $value[0]['divisi'] }}</td>
                        <td style="text-align:center;">{{ $value[0]['jam_masuk'] }}</td>
                        <td style="text-align:center;">{{ $value[0]['lokasi_absen_masuk'] }}</td>
                        <td>{{ $value[0]['detail_lokasi_absen_masuk'] }}</td>
                        <td style="text-align:center;">{{ $value[0]['jam_keluar'] }}</td>
                        <td style="text-align:center;">{{ $value[0]['lokasi_absen_pulang'] }}</td>
                        <td>{{ $value[0]['detail_lokasi_absen_masuk'] }}</td>
                        <td style="text-align:center;">Non Shift</td>
                        <td>{{ $value[0]['catatan']  }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endforeach
    </table>
    @endforeach

</body>
</html>
