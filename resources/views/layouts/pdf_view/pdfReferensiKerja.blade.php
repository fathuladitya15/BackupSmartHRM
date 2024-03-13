<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Referensi Kerja     {{ $data->nama_karyawan }}</title>
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

        .no_sp{
            z-index: 9999999999999;
            position: absolute;
            top: 170px;
            right: 3px;
            font-size: 80%;
        }

        .over_text{
            margin-top: -50px !important;
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

                    <p class="small">
                        Alamanda Tower, <br>
                        Jl. TB Simatupang No.22-26 21st Floor, Unit 21-F,<br>
                         Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430
                    </p>
                </td>
                <td width="25">
                   <p class="no_sp" ></p>
                </td>
            </tr>
        </table>
        <hr>
        <div class="row">
            <div class="col-lg-12" style="text-align: center">
                <h4><b><u>SURAT REFERENSI KERJA</u></b></h4>    <br>
                <p style="margin-top: -40px !important;">Nomor : {{ $data->no_surat }}</p>
            </div>
        </div>
        <br>


        <br>
        <p style="font-size : 80%">Yang bertandatangan di bawah ini Direktur Alih Daya PT. Proven Force Indonesia, menerangkan bahwa:</p>
        <div class="row" style="text-items: center; padding-left: 40px;font-size:80%">
            <table class=""  width="100%" style="align-content: center;" >
                <tr>
                    <td>Nama</td>
                    <td width="10">:</td>
                    <td id="nama_karyawan">{{ $data->nama_karyawan }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td width="10">:</td>
                    <td id="nik">{{ $data->nik }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td width="10">:</td>
                    <td id="alamat">{{ $data->alamat }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td width="10">:</td>
                    <td id="jabatan">{{ $data->jabatan }}</td>
                </tr>
            </table>
        </div>
        <br><br>
        <div class="row" id="text" style="font-size: 80%;">
            <p id="pertama">
                {{ $data->teks_keterangan }}
            </p>
        </div>

        <br><br>
        <div class="row" style="font-size: 80%;">
            <div class="tanggal_pembuatan" id="tanggal_pembuatan">{{ Carbon\Carbon::parse($data->tanggal_pembuatan)->translatedFormat('l, d F Y') }}
            </div>
            <div class="">
                PT. Proven Force Indonesia
            </div>
        </div>
        <div class="row"style="font-size: 80%;">
            <div class="col-lg-6">
                <img class="gambar_tanda_tangan_kr" src="{{ public_path($data->ttd_direktur) }}" alt="" id="gambar_tanda_tangan_kr" width="100"> <br  />
                <div class="over_text">
                    <p  id="nama_ttd_karyawan"><b><u>{{ Str::title($data->nama_direktur) }}</u></b> <br>
                        Direktur Alih Daya
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
