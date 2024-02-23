@extends('vBase')
@section('title') {{ 'Detail Data Karyawan' }} @endsection
@push('css')
    <style>
        h4{
            color: white;
        }
        .active-acc {
            background-color: white;
        }
    </style>
@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan / Detail Karyawan /</span> {{ $data_users->name }} - {{ $data_users->id_karyawan }} </h4>
@include('layouts.akun.menu_akun')
@include( $view )
@endsection
