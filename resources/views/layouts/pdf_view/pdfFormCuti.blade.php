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
            font-size: 110%;
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
                    <h5>DATA / FORM PERMOHONAN CUTI KARYAWAN</h5>
                </td>
                <td width="25">
                </td>
            </tr>
        </table>
        <hr>
        <br><br>
        <table width="100%"  >
            <tr>
                <td width="150">Nama</td>
                <td width="10" style="text-align: center">:</td>
                <td>{{ Str::title( $data->nama_karyawan) }}</td>
            </tr>
            <tr>
                <td>Divisi</td>
                <td style="text-align: center">:</td>
                <td>{{ Str::title($data->divisi) }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td style="text-align: center">:</td>
                <td>{{ Str::title($data->jabatan) }}</td>
            </tr>
            <tr>
                <td>Alasan Cuti</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->alasan }}</td>
            </tr>
            <tr>
                <td>Kategori Cuti</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->kategori_cuti }}</td>
            </tr>
            <tr>
                <td>Mulai Tanggal / Sampai Tanggal </td>
                <td style="text-align: center">:</td>
                <td>{{ Carbon\Carbon::parse($data->start_date)->translatedFormat('l, d F Y')  }}
                        s/d
                    {{ Carbon\Carbon::parse($data->end_date)->translatedFormat('l, d F Y')  }}
                </td>
            </tr>
            <tr>
                <td>Total Cuti</td>
                <td style="text-align: center">:</td>
                <td>{{ $data->ambil_cuti }} hari</td>
            </tr>


        </table>

        <br><br><br>

        @if($jabatan == 'Manager')
        <div class="row" style="text-align: center">
            <div class="col-lg-6">
                Pemohon
            </div>
            <div class="col-lg-6" style="text-align: center">
                Disetujui
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-lg-6">
                <img  src="{{ public_path($data->ttd_karyawan) }}" alt="" style="width: 100px;" ><br>
                <div class="name_ttd" style="left:210px !important;" >{{ Str::title($data->nama_karyawan) }}</div>
            </div>

            <div class="col-lg-6">
                <img  src="{{ $data->ttd_direktur }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >Direktur HRD PT.PFI</div>
            </div>
        </div>
        @else
        <div class="row" style="text-align: center">
            <div class="col-lg-3">
                Pemohon
            </div>

            <div class="col-lg-6" style="text-align: center">
                Mengetahui,
            </div>

            <div class="col-lg-3" style="text-align: center">
                Disetujui
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
                <img  src="{{ $data->ttd_hrd }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >Supervisor HRD</div>
            </div>

            <div class="col-lg-3">
                <img  src="{{ $data->ttd_direktur }}" alt="" style="width: 100px;"> <br />
                <div class="name_ttd" >Direktur HRD PT.PFI</div>
            </div>
        </div>
        @endif

    </div>
</body>
</html>
