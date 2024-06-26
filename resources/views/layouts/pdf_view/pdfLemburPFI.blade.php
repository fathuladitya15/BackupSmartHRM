<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lembur {{ $data->nama_karyawan }}</title>
    <style>


        .row{ clear: both; }
        .col-lg-1 {width:8%;  float:left;}
        .col-lg-2 {width:16%; float:left;}
        .col-lg-3 {width:25%; float:left;}
        .col-lg-4 {width:33%; float:left;}
        .col-lg-5 {width:42%; float:left;}
        .col-lg-6 {width:50%; float:left;}
        .col-lg-7 {width:58%; float:left;}
        .col-lg-8 {width:66%; float:left;}
        .col-lg-9 {width:75%; float:left;}
        .col-lg-10{width:83%; float:left;}
        .col-lg-11{width:92%; float:left;}
        .col-lg-12{width:100%; float:left;}
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .kop_surat{
            margin: auto;
            text-align: center
        }
        .kop_surat img{
            width: 50%;
        }
        p.small {
            line-height: 0.9;
            font-size: 70%;
        }
        table img{
            width: 50%;
        }
        .name_ttd {
            margin-top: -50px !important;
        }
        table {
            border-collapse: collapse;
            font-size: 80%;
        }
        th, td {
            padding: 5px;
          }



    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td width="25" ><img src="{{ public_path('assets/img/layouts/logo-pfi-dark.png') }}" alt=""></td>
                <td width="50" align="center">
                    <h3>PT PROVEN FORCE INDONESIA</h3>
                    <h5>FORM INSTRUKSI LEMBUR</h5>
                    <h5>Tanggal {{ Carbon\Carbon::parse($data->tanggal_lembur)->translatedFormat('d F Y') }}</h5>
                </td>
                <td width="25">
                </td>
            </tr>
        </table>
        <hr>
        <br><br>
        <table width="100%" border="1" >
            <tr>
                <td width="150">Nama</td>
                <td width="10" style="text-align: center">:</td>
                <td>{{ $data->nama_karyawan }}</td>
            </tr>
            <tr>
                <td>Divisi</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->divisi }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->jabatan }}</td>
            </tr>
            <tr>
                <td>Lokasi Kerja</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->lokasi_kerja }}</td>
            </tr>
            <tr>
                <td>Alasan Lembur</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->alasan_lembur }}</td>
            </tr>
            <tr>
                <td>Tugas yang dikerjakan</td>
                <td style="text-align: center">:</td>
                <td > {{ $data->tugas }}</td>
            </tr>

        </table>

        <table width="100%" border="1" style="text-align:center;">
            <tr>
                <td>Mulai Jam</td>
                <td>Sampai Jam</td>
                <td>Jumlah Jam</td>
            </tr>
            <tr>
                <td>{{ $data->jam_mulai }} </td>
                <td>{{ $data->jam_selesai }}</td>
                <td>{{ $data->total_jam }} Jam </td>
            </tr>
        </table>
        <br><br><br>

        <div class="row" style="text-align: center;">
            <div class="col-lg-3">
                Penerima Instruksi
            </div>
            <div class="col-lg-3">
                Pemberi Instruksi
            </div>
            <div class="col-lg-3">
                Supervisor HRD
            </div>
            <div class="col-lg-3">
               Mengetahui,
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-lg-3">
                <img  src="{{ public_path($data->ttd_karyawan) }}" alt="" style="width: 100px;" ><br>
                <div class="name_ttd" style="left:210px !important;" >{{ Str::title($data->nama_karyawan) }}</div>
            </div>
            <div class="col-lg-3">
                <img  src="{{ $data->ttd_manager }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >Manager Divisi</div>
            </div>
            <div class="col-lg-3">
                <img  src="{{ $data->ttd_admin_korlap }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >{{ $nama_admin }}</div>
            </div>
            <div class="col-lg-3">
                <img  src="{{ $data->ttd_direktur }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >Direktur HRD PT.PFI</div>
            </div>
        </div>

    </div>
</body>
</html>
