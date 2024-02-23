<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lembur {{ $nama_karyawan }}</title>
    <style>

        @media print {
            .pagebreak { page-break-before: always; } /* page-break-after works, as well */
        }
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
    @foreach ($data as $dt )

        <div class="container">
            <table width="100%">
                <tr>
                    <td width="25" ><img src="{{ public_path('assets/img/layouts/logo-pfi-dark.png') }}" alt=""></td>
                    <td width="50" align="center">
                        <h3>PT PROVEN FORCE INDONESIA</h3>
                        <h5>FORM INSTRUKSI LEMBUR</h5>
                        <h5>Tanggal {{ Carbon\Carbon::parse($dt->tanggal_lembur)->translatedFormat('d F Y') }}</h5>
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
                    <td>{{ $dt->nama_karyawan }}</td>
                </tr>
                <tr>
                    <td>Divisi</td>
                    <td style="text-align: center">:</td>
                    <td>{{ $dt->divisi }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td style="text-align: center">:</td>
                    <td>{{ $dt->jabatan }}</td>
                </tr>
                <tr>
                    <td>Lokasi Kerja</td>
                    <td style="text-align: center">:</td>
                    <td>{{ $dt->lokasi_kerja }}</td>
                </tr>
                @if (in_array(Auth::user()->id_client,[2,8]))
                    <tr>
                        <td>Batch No.</td>
                        <td style="text-align: center">:</td>
                        <td>{{ $dt->batch }}</td>
                    </tr>
                    <tr>
                        <td>Group</td>
                        <td style="text-align: center">:</td>
                        <td>{{ $dt->group }}</td>
                    </tr>

                @endif
                <tr>
                    <td>Alasan Lembur</td>
                    <td style="text-align: center">:</td>
                    <td>{{ $dt->alasan_lembur }}</td>
                </tr>
                <tr>
                    <td>Tugas yang dikerjakan</td>
                    <td style="text-align: center">:</td>
                    <td > {{ $dt->tugas }}</td>
                </tr>

            </table>

            <table width="100%" border="1" style="text-align:center;">
                <tr>
                    <td>Mulai Jam</td>
                    <td>Sampai Jam</td>
                    <td>Jumlah Jam</td>
                </tr>
                <tr>
                    <td>{{ $dt->jam_mulai }} </td>
                    <td>{{ $dt->jam_selesai }}</td>
                    <td>{{ $dt->total_jam }} Jam </td>
                </tr>
            </table>
            <br><br><br>

            <div class="row" style="text-align: center;">
                <div class="col-lg-6">
                    Penerima Instruksi
                </div>
                <div class="col-lg-6">
                    Pemberi Instruksi
                </div>
            </div>
            <div class="row" style="text-align: center;">
                <div class="col-lg-6">
                    <img  src="{{ public_path($dt->ttd_karyawan) }}" alt="" style="width: 100px;" ><br>
                    <div class="name_ttd" style="left:210px !important;" >{{ Str::title($dt->nama_karyawan) }}</div>
                </div>
                @php
                    $id_kr_admin    = App\Models\Filemanager::where('path',$dt->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
                    $nama_admin     = App\Models\User::where('id_karyawan',$id_kr_admin)->first()->name;
                @endphp
                <div class="col-lg-6">
                    <img  src="{{ $dt->ttd_admin_korlap }}" alt="" style="width: 100px;"> <br />
                    <div class="name_ttd" >{{ $nama_admin }}</div>
                </div>
            </div>

        </div>
        <div class="pagebreak"> </div>
    @endforeach
</body>
</html>
