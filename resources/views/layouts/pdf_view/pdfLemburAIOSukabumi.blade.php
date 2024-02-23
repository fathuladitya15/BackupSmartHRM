<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $filename }}</title>
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


        .kotak{
            border: solid;
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
                    <h5><u>SURAT PERINTAH KERJA LEMBUR</u></h5>
                </td>
                <td width="25">
                </td>
            </tr>
        </table>
        <hr>
        <br>
        <table width="30%" style="float: left">
            <tr>
                <td>Divisi</td>
                <td>:</td>
                <td>{{ Str::title ($data->divisi) }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ Carbon\Carbon::parse($data->tanggal_lembur)->translatedFormat("d F Y") }}</td>
            </tr>
        </table>
        <table width="30%"  style="float: right">
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ Str::title($data->jabatan)  }}</td>
            </tr>
            <tr>
                <td>Shift</td>
                <td>:</td>
                @php
                    $shift = App\Models\Shift::find($data->id_shift);

                @endphp
                <td>{{ $shift->type }} {{ $shift->ke }}</td>
            </tr>
        </table>
        <br><br><br>
        <table width="100%" border="1" style="text-align: center" >
            <tr>
                <td rowspan="2">No</td>
                <td rowspan="2">ID Karyawan</td>
                <td rowspan="2">Nama Karyawan</td>
                <td colspan="2">Aktivitas Reguler</td>
                <td colspan="2">Rencana Lembur</td>
                <td colspan="2">Lembur Aktual</td>
                <td rowspan="2">ket</td>
            </tr>
            <tr>
                <td>Mulai</td>
                <td>Selesai</td>
                <td>Mulai</td>
                <td>Selesai</td>
                <td>Mulai</td>
                <td>Selesai</td>
            </tr>
            <tr>
                <td>1</td>
                <td>{{ $data->id_karyawan }}</td>
                <td>{{ $data->nama_karyawan }}</td>
                <td>{{ $data->jam_mulai }}</td>
                <td>{{ $data->jam_selesai }}</td>
                <td>{{ $data->jam_mulai_rl }}</td>
                <td>{{ $data->jam_selesai_rl }}</td>
                <td>{{ $data->jam_mulai_la }}</td>
                <td>{{ $data->jam_selesai_la }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left; vertical-align: top; font-size:80%;" height="100">Rencana Aktifitas : {{  $data->alasan_lembur }}</td>
                <td colspan="6" style="text-align: left; vertical-align: top; font-size:80%;" height="100">Aktual Aktifitas : {{  $data->tugas }}</td>
            </tr>

        </table>

        <br><br><br>

        <div class="row" style="text-align: center; font-size:90%;">
            <div class="col-lg-3">
                Dibuat
            </div>
            <div class="col-lg-3">
                Diketahui
            </div>
            <div class="col-lg-6">
                Disetujui,
            </div>
        </div>
        <div class="row" style="text-align: center; font-size:90%;">
            <div class="col-lg-3">
                <img  src="{{ public_path($data->ttd_karyawan) }}" alt="" style="width: 100px;" ><br>
                <div class="name_ttd" style="left:210px !important;" >{{ Str::title($data->nama_karyawan) }}</div>
            </div>
            <div class="col-lg-3">
                <img  src="{{ $data->ttd_admin_korlap }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >{{ $nama_admin }}</div>
            </div>
            <div class="col-lg-3">
                <div class="" style="left:210px !important;padding-top:50px;" >Leader</div>
            </div>
            <div class="col-lg-3">
                <div class="" style="left:210px !important;padding-top:50px;" >Ass. Manajer</div>
            </div>
        </div>


    </div>
</body>
</html>
