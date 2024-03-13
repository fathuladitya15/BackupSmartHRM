
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
    <table class="table table-striped" border="1" width="100%">
        <thead>
            <tr>
                <th style="text-align:center; font-size:80%" colspan="12"> <b>({{ \Carbon\Carbon::parse($from_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($to_date)->translatedFormat('d F Y') }})</b></th>
            </tr>
            <tr class="text-nowrap" style="font-size: 70%">
                <th>No</th>
                <th>Tanggal</th>
                <th>Jabatan</th>
                <th>Divisi</th>
                <th>Absen Masuk</th>
                <th>Mulai_istirahat </th>
                <th>Selesai Istirahat</th>
                <th>Absen Pulang</th>
                <th>Lokasi Absen</th>
                <th>shift</th>
                <th>Detail Lokasi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($data as $item)
                <tr style="text-align: center;font-size:60%">
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-y')  }}</td>
                    <td>{{ $item->jabatan }}</td>
                    <td>{{ $item->divisi }}</td>
                    <td>{{ $item->jam_masuk }}</td>
                    <td>{{ $item->mulai_istirahat }}</td>
                    <td>{{ $item->selesai_istirahat }}</td>
                    <td>{{ $item->jam_keluar }}</td>
                    <td>{{ $item->lokasi_absen_plg }}</td>
                    <td>{{ $item->shift }}</td>
                    <td>{{ $item->detail_lokasi_absen_plg }}</td>
                    <td>{{ $item->catatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
