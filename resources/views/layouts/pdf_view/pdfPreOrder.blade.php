<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $judul }}</title>
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
          }

          th, td {
              padding-top: 5px;
              padding-bottom: 5px;
              padding-left: 5px;
              padding-right: 5px;
            }
            img{
                width: 100%;
            }

        .sub_ttd{
            margin-top: -200px !important;
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
                    <h5>{{ $judul }}</h5>
                </td>
                <td width="25">
                </td>
            </tr>
        </table>
        <hr>
        <br><br>
        <table width="100%" border="1" style="font-size:80%;" >
            <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td>Divisi</td>
                <td>Batas Waktu</td>
                <td>Nama  Barang</td>
                <td>Total Barang</td>
                <td>Jenis Satuan</td>
                <td>Biaya /unit</td>
                <td>Jumlah</td>
                <td>Keterangan</td>
            </tr>
            @php
                $no = 1;
            @endphp
            @foreach ($data_barang as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}</td>
                <td>{{ $data->divisi }}</td>
                <td>{{ \Carbon\Carbon::parse($data->batas_waktu)->translatedFormat('l, d F Y') }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td style="text-align: right">{{ $item->unit }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ number_format($item->biaya,0,',','.') }}</td>
                <td>
                    @php
                        $penjumlahan = (int)$item->unit * (int)$item->biaya;
                    @endphp
                    {{ number_format($penjumlahan,0,',','.') }}
                </td>
                <td>{{ $item->keterangan }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="8" style="text-align: right">Total</td>
                <td colspan="2">{{ number_format($jumlah,0,',','.') }}</td>
            </tr>
        </table>


        <br><br><br>

        <div class="row" style="text-align: center;">
            <div class="col-lg-6">
                Diajukan Oleh
            </div>
            <div class="col-lg-6">
                Disetujui Oleh
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-lg-6">
                <img  src="{{ $data->ttd_pembuat != null ?  public_path($data->ttd_pembuat) : 'Error' }}" alt="" style="width: 100px;" ><br>
                <div class="name_ttd" style="left:210px !important;" > <u>{{ Str::title($data->dibuat_oleh) }}</u></div> <br>
                <div class="name_ttd"></div>
            </div>
            <div class="col-lg-6">
                <img  src="{{   public_path($data->ttd_direktur) }}" alt="" style="width: 100px;"> <br>
                {{-- <div class="name_ttd" >{{ $data->approved_by }}</div> --}}
                <div class="name_ttd" ><u>{{ $data->nama_direktur }}</u></div> <br>
                <div class="name_ttd">(Direktur Alih Daya)</div>
            </div>
        </div>

    </div>
</body>
</html>
