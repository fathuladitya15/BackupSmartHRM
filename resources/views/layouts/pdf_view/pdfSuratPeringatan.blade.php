<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Peringatan {{ $data->nama_karyawan }}</title>
    <style>
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


    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td width="25" ><img src="{{ public_path('assets/img/layouts/logo-pfi-dark.png') }}" alt=""></td>
                <td width="50" align="center">
                    <h3>PT Proven Force Indonesia</h3>

                    <p class="small">
                        Alamanda Tower, <br>
                        Jl. TB Simatupang No.22-26 21st Floor, Unit 21-F,<br>
                         Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430
                    </p>
                </td>
                <td width="25">
                   <p class="no_sp" >NO. SP : {{ $data->no_surat }}</p>
                </td>
            </tr>
        </table>
        <hr>
        <br><br>
        <table width="100%" >
            <tr>
                <td width="9" style="font-size: 80%">Lamp</td>
                <td align="center">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td style="font-size: 80%">Hal</td>
                <td align="center">:</td>
                <td style="font-size: 80%">Surat Peringatan {{  $data->tipe_peringatan == 1 ? 'Pertama' : 'Ke - '.$data->tipe_peringatan  }}</td>
            </tr>
        </table>
        <br>
        <table  style="font-size:80%" >
            <tr >
                <td>Surat Peringatan ini ditunjukan kepada</td>
                <td>:</td>
                <td></td>
            </tr>

        </table>
        <table style="font-size:80%" >
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>  <b>{{ $data->nama_karyawan }}</b> </td>
            </tr>
            <tr>
                <td>ID Karyawan</td>
                <td>:</td>
                <td>{{ $data->karyawan_id }}</b> </td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $data->jabatan }}</b> </td>
            </tr>
            <tr>
                <td>Lokasi Kerja</td>
                <td>:</td>
                <td>{{ $lokasi_kerja }}</td>
            </tr>
            <tr>
                <td>Diberikan Oleh</td>
                <td>:</td>
                <td></td>
            </tr>

            <tr>
                <td>HRD Personalia</td>
                <td>:</td>
                <td>{{ $data->nama_pemberi_sp }}</td>
            </tr>
        </table>

        <br>
        <p style="font-size : 80%">Surat ini dikeluarkan karena adanya beberapa faktor. {{ $data->alasan }}</p>
        <p style="font-size:80%">{{ $data->keterangan }}</p>



        <br><br>
        <table width="100%" style="text-align: right; font-size:80%;">
            <tr>
                <td> <p> {{  Carbon\Carbon::parse($data->tanggal_pembuatan)->translatedFormat('l, d F Y')    }} </p> </td>
            </tr>

        </table>
        <br>
        <table  style="font-size: 80%" >
            <tr style="text-align: center">
                <td width="80" >Nama Karyawan</td>
                <td width="350"></td>
                <td width="80">HRD Personalia</td>
            </tr>
        </table>
        <br>
        <br><br>
        <table  style="font-size: 100%" >
            <tr style="text-align: center">
                <td width="90" style="font-size: 80%">{{ $data->nama_karyawan }}</td>
                <td width="340"></td>
                <td width="90" style="font-size:80%">{{ $data->disetujui_oleh }}</td>
            </tr>
            <tr style="text-align: center">
                <td width="90"></td>
                <td width="340"></td>
                <td width="90" style="font-size:80%"></td>
            </tr>
        </table>
    </div>
</body>
</html>
