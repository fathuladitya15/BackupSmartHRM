@extends('vBase')
@section('title') {{ 'Profile Anda' }} @endsection
@push('css')
<style>
    .black {
        color: black;

    }
    h4 {
        color: white;
    }
    h5 {
        color: white;
    }
    .card-title {
        padding-right: 30px;
        border-left-width: 30px;
        padding-left: 30px;

    }
    .card-header {
        background-color:#47b4f5;
        margin-bottom: 20px;
    }

    .profile-view{
        position: relative;
    }

    .profile-view .profile-img-wrap{
        height: 120px;
        width: 120px;
        position: absolute;
    }
    .profile-basic{
        margin-left: 140px;
        padding-right: 50px;
    }
    .profile-view .profile-basic .profile-info-left {
        border-right: 2px dashed #D3D3D4;
    }
    .profile-view .profile-basic .profile-info-left .staff-id {
        font-size: 14px;
        font-weight: 500;
        margin-top: 5px;
    }
    .personal-info{
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .personal-info li .title {
        color: #373B3E;
        float: left;
        font-weight: 500;
        width: 30%;
    }
    .personal-info li .text {
        color: #7A7C7F;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }
    .nav-tabs {
        border-bottom: 1px solid #D5D8DA;
    }

    .swal2-container {
        z-index: 999999;
    };
    </style>

@endpush
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Profile</h4>

<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <img src="{{ $foto_profile }}" alt="user-avatar" class="d-block rounded" height="120" width="120" id="uploadedAvatar" />
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                    <div class="profile-info-left">
                                    <h3 class="mb-0">{{ Str::title(Auth::user()->name) }}</h3>
                                    <h6 class="mb-0">{{ $jabatan }}</h6>
                                    <small>{{ $divisi }}</small>
                                    <br   />
                                    <div class="staff-id">ID Karyawan :  {{ Auth::user()->id_karyawan }}</div>
                                    <div class="small">Bergabung sejak : {{ $join_date }}</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">No. Handphome:</div>
                                        <div class="text"><a href="">{{ $kr->no_hp }}</a></div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text"><a href="">{{ Auth::user()->email }}</a></div>
                                    </li>
                                    <li>
                                        <div class="title">Tanggal Lahir:</div>
                                        <div class="text">{{ \Carbon\Carbon::parse($kr->tanggal_lahir)->translatedFormat("d F Y") }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Alamat:</div>
                                        <div class="text">{{ $kr->alamat }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Jenis Kelamin:</div>
                                        <div class="text">{{ $kr->jenis_kelamin == 'L' ? 'Laki - Laki' : 'Perempuan' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
              <a class="nav-link {{ Request::segment(2) == 'akun' ? 'active' : '' }}" href="{{ route("profile",['menu' => 'akun']) }}"><i class='bx bxs-lock-alt'></i> Akun</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(2) == 'datadiri' ? 'active' : '' }}" href="{{ route("profile",['menu' => 'datadiri']) }}"><i class="bx bx-user me-1"></i> Data Diri</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(2) == 'karyawan' ? 'active' : '' }}" href="{{ route("profile",['menu' => 'karyawan']) }}"><i class='bx bxs-buildings'></i> Karyawan </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Request::segment(2) == 'dokumen' ? 'active' : '' }}" href="{{ route("profile",['menu' => 'dokumen']) }}"><i class='bx bxs-file-blank' ></i> Dokumen</a>
            </li>
          </ul>
    </div>
</div>

@include($v)

@endsection


