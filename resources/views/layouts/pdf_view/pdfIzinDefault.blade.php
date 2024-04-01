<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Izin {{ $data->nama_karyawan }}</title>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> --}}
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
            position: absolute;
            bottom: 140px;
            left: 230px;
        }
        p#nama_ttd_karyawan {
            margin-top: -50px !important;
        }
        #footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 15px !important;
            /* text-align: center; */
            line-height: 35px;
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
                        <h5>FORM PENGAJUAN IZIN</h5>
                        <h5>No.Surat : {{ $data->no_surat }}</h5>
                </td>
                <td width="25">
                </td>
            </tr>
        </table>
        <hr>
        <br><br>

            <table width="100%" >
                <tr>
                    <td>ID Karyawan</td>
                    <td>:</td>
                    <td>{{ $data->karyawan_id }}</td>
                </tr>
                <tr>
                    <td width="150">Nama</td>
                    <td width="10">:</td>
                    <td>{{ Str::title($data->nama_karyawan) }}</td>
                </tr>
                <tr>
                    <td>Divisi</td>
                    <td>:</td>
                    <td>{{ Str::title($data->divisi) }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ Str::title($data->jabatan) }}</td>
                </tr>
                <tr>
                    <td>Alasan</td>
                    <td>:</td>
                    <td>{{ Str::title($data->alasan) }}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td > {{ Carbon\Carbon::parse($data->tanggal_pembuatan)->translatedFormat("d F Y")  }}, Pukul  {{ $data->jam_keluar }} WIB ,  ( {{ $data->kembali == 0 ? 'Kembali' : "Tidak Kembali" }} )</td>
                </tr>
                <tr>
                    <td>Jelaskan</td>
                    <td>:</td>
                    <td> {{ $data->detail }}</td>
                </tr>

            </table>
            <br>
            <table width="100%" style="text-align: right">
                <tr>
                    <td >Gunung Putri, {{ Carbon\Carbon::parse($data->tanggal_pembuatan)->translatedFormat("d F Y") }}</td>
                </tr>
            </table>
            <br><br><br>


            <div class="row" style="text-align: center">
                <div class="col-lg-6">
                    Pemohon
                </div>
                <div class="col-lg-6">
                    Mengetahui
                </div>
            </div>


            <div class="row" style="text-align:center;">
                <div class="col-lg-6">
                    <img class="gambar_tanda_tangan_kr" src="{{ public_path($data->ttd_karyawan) }}" alt="" id="gambar_tanda_tangan_kr" width="100"> <br  />
                    <div class="over_text">
                        <p  id="nama_ttd_karyawan">{{ Str::title($data->nama_karyawan) }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img class="gambar_tanda_tangan_admin" src="{{ public_path($data->ttd_mengetahui)}}" alt="" id="gambar_tanda_tangan_admin" width="100"> <br  />
                    <div class="over_text">
                        <p  id="nama_ttd_karyawan" >{{ Str::title($data->user_id_mengetahui) }}</p>
                    </div>
                </div>
            </div>

    </div>
</body>
</html>
