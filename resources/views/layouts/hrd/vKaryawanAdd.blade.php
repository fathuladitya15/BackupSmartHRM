@extends('vBase')
@section('title') {{ 'Tambah Karyawan' }} @endsection
@push('css')
<style>
    h4 {
        color: white;
    }
    h5 {
        color: white;
    }
    .circular_image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        background-color: blue;
        display:inline-block;
        vertical-align:middle;
    }
    .circular_image img{
        width:100%;
    }

    .card-header {
        background-color:#47b4f5;
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>

@endpush
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan/</span> Tambah Karyawan</h4>
<button style="float: right;" class="btn btn-success" id="upload_excel"> <i class="fa-solid fa-plus"></i> Upload Data Karyawan</button>
<br><br>
@if ($errors->any())
@foreach ($errors->all() as $item)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $item }}
        <button type="button" class="btn btn-default " style="float: right;" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endforeach
@endif
<!-- Basic Layout & Basic with Icons -->
<form id="FormsData"  enctype="multipart/form-data" action="{{ route('karyawan-saving') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between" >
                    <h5 class="mb-0">Profile</h5>
                </div>
                <div class="card-body">
                    <br>
                    <!-- PROFILE FORM -->
                    <div class="row">
                        <div class="form-group col-md-3" style="text-align: center">
                            <img src="{{ asset('assets/img/avatars/default.jpg') }}" alt="Gambar" id="preview"  class="circular_image" style="align-content: center">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload Foto</label>
                            <input class="form-control " placeholder="" name="profile_pictures" type="file" id="profile_pictures">

                        </div>
                        <div class="form-group col-md-3" >
                            <label for="gender" class="col-form-label">Status Akun</label><span class="text-danger pl-1">*</span>
                            <br>
                            <div class="d-flex radio-check" style="align-contents:center">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="status_a" value="0" required  name="status_account" {{ @old('status_account') == '0' ? 'checked' : '' }} class="form-check-input" >
                                    <label class="form-check-label " for="status_a"><span class="badge bg-success" >Aktif</span></label>
                                </div>
                                <div class="custom-control custom-radio ms-1 custom-control-inline">
                                    <input type="radio" id="status_n" value="1" name="status_account" {{ @old('status_account') == '1' ? 'checked' : '' }} class="form-check-input" >
                                    <label class="form-check-label " for="status_n"><span class="badge bg-danger" >Tidak Aktif</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /PROFILE FORM -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- DATA PRIBADI FORM -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between" >
                    <h5 class="mb-0">Data Pribadi</h5>
                </div>
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nama Lengkap</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control "  placeholder="Nama Lengkap Karyawan"  value="{{ @old('name') }}" name="name" type="text" id="name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">No. Handphone</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"  placeholder="08xxxxxxxxxx"  value="{{ @old('nohp') }}" name="nohp" type="number" id="nohp" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tempat Lahir</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="TmptLhr"  value="{{ @old('TmptLhr') }}" type="text" id="TmptLhr" placeholder="Jakarta" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Lahir</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="tglLhr"  value="{{ @old('tglLhr') }}"  type="date" id="tglLhr" required>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="gender" class="col-form-label">Jenis Kelamin</label><span class="text-danger pl-1">*</span>
                            <select name="jk" id="jk" class="form-control" required >
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ @old('jk') == 'L' ? 'selected' : '' }}>Laki - Laki</option>
                                <option value="P" {{ @old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gender" class="col-form-label">Pendidikan</label><span class="text-danger pl-1">*</span>

                            <select name="pendidikan" id="pendidikan" class="form-control" required>
                                <option value="">-- Pilih Pendidikan --</option>
                                <option value="SD"  {{ @old('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ @old('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ @old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="D1"  {{ @old('pendidikan') == 'D1' ? 'selected' : '' }}>D1</option>
                                <option value="D2"  {{ @old('pendidikan') == 'D2' ? 'selected' : '' }}>D2</option>
                                <option value="D3"  {{ @old('pendidikan') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1"  {{ @old('pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2"  {{ @old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3"  {{ @old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Email</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"  placeholder="Email@mail.com" value="{{ @old('email') }}" name="email" type="email" id="email" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">Password</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"  placeholder="********" name="password" type="password" id="password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="alamat">Alamat Ktp</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea  id="alamat"  rows="1" cols="10"  name="alamat"   class="form-control" required>{{{ @old('alamat') }}}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="alamat_domisili">Alamat Domisili</label>
                            <span class="text-danger pl-1">*</span>
                            <textarea  id="alamat_domisili"  rows="1" cols="50" name="alamat_domisili"  required class="form-control">{{{ @old('alamat_domisili') }}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="alamat">Usia</label><span class="text-danger pl-1">*</span>
                            <input type="text" name="usia" id="usia" class="form-control" value="{{ @old('usia') }}"  placeholder="Masukan Usia">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="alamat">Agama</label><span class="text-danger pl-1">*</span>
                            <select name="agama" id="agama" class="form-control" required >
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam"  {{ @old('agama') =='Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen"  {{ @old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik"  {{ @old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu"  {{ @old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha"  {{ @old('agama') == 'Budha' ? 'selected' : '' }}>Budha</option>
                                <option value="Konghucu"  {{ @old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="">Nama Suami/Istri</label>
                            <input type="text" name="nama_suami_istri" value="{{ @old('nama_suami_istri') }}"  id="nama_suami_istri" class="form-control"  placeholder="Masukan Nama Suami/Istri">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="">Nama Anak</label>
                            <input type="text" name="nama_anak" id="nama_anak" class="form-control"  placeholder="Nama Anak 1, Nama Anak 2, dst" value="{{ @old('nama_anak') }}" >
                            <p style="font-size: 70%;">*) Gunakan ( , / koma ) apabila nama anak lebih dari 1</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="">Jumlah Anak</label>
                            <input type="text" name="jumlah_anak" id="jumlah_anak" class="form-control" value="{{ @old('jumlah_anak') }}"   placeholder="Masukan Jumlah Anak">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="">Nama Ibu</label><span class="text-danger pl-1">*</span>
                            <input type="text" name="nama_ibu" id="nama_ibu" value="{{ @old('nama_ibu') }}"  class="form-control"  placeholder="Masukan Nama Ibu">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="">Nama Bapak</label><span class="text-danger pl-1">*</span>
                            <input type="text" name="nama_bapak" id="nama_bapak" class="form-control" value="{{ @old('nama_bapak') }}"  placeholder="Nama Bapak">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="">Tipe Akun</label><span class="text-danger pl-1">*</span>
                            <select name="tipe_akun" id="tipe_akun" class="form-control" required  >
                                <option value="">-- Pilih Tipe Akun --</option>
                                @foreach ($tipe_akun as $t )
                                    <option value="{{ $t->slug_role }}" {{ @old("tipe_akun") == $t->slug_role ? 'selected' : '' }}>{{ $t->name_role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /DATA PRIBADI FORM -->


        <!-- DATA KARYAWAN FORM -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Data Karyawan</h5>
                </div>
                <div class="card-body">
                    <br  />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">ID Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"  placeholder="Masukan ID Card" name="karyawan_id" value="{{ @old('karyawan_id') }}"  type="text" id="karyawan_id">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="">Kategori</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="kategori_pengguna" id="kategori_pengguna" class="form-control" required>
                                <option value="">-- Pilih Kategori Karyawan --</option>
                                <option value="pusat" {{ @old('kategori_pengguna')      == 'pusat' ? 'selected' : '' }}>Karyawan Pusat (Kantor PT.PFI)</option>
                                <option value="project" {{ @old('kategori_pengguna')    == 'project' ? 'selected' : '' }}>Karyawan Internal Pusat (Project)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Lokasi Kerja</label>
                            <span class="text-danger pl-1">*</span>
                            <button type="button" class="btn btn-sm " style="size: 10%"  data-toggle="tooltip" data-placement="top" title="Pilih lokasi kerja sesuai apa yang ditugaskan. Contoh karyawan PT. PFI yang ditugasakan untuk menjadi bagian dari PT. Lain. Jadi lokasi kerjanya yaitu PT.Lain dengan KATEGORI Karyawan Pusat">

                            <i class='bx bxs-info-circle'></i>
                            </button>
                            <select name="branch" id="branch" class="form-control" required="required">
                                <option value="">-- Pilih Lokasi Kerja --</option>
                                @foreach ($client as $t )
                                    <option value="{{ $t->id }}" {{ @old('branch') == $t->id ? 'selected' : '' }}>{{ $t->nama_client }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="jabatan" id="jabatan" class="form-control" required="required">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $aj )
                                    <option value="{{ $aj->id }}" {{ @old('jabatan') == $aj->id ? 'selected' : '' }}>{{ $aj->nama_jabatan }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group-12">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class=" col-form-label" for="basic-default-name">Pilih Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="divisi" id="divisi" class="form-control" required="required" >
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisi as $dv )
                                    <option value="{{ $dv->id }}" {{ @old('divisi') == $dv->id ? 'selected' : '' }}>{{ $dv->nama_divisi }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Bergabung </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control" required="required" name="join_date" id="join_date" value="{{ @old('join_date') }}" >
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Selesai </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control" required="required" name="end_date" id="end_date" value="{{ @old("end_date") }}" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class=" col-form-label" for="basic-default-name">No.SIO</label>
                            <input type="text" class="form-control" name="noSio" id="noSio" placeholder="-" value="{{ @old('noSio') }}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class=" col-form-label" for="basic-default-name">Tunjangan</label>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" name="tJabatan" id="tJabatan" placeholder="0" value="{{ @old("tJabatan") }}">
                              </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Transportasi</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" name="tTransportasi" id="tTransportasi" placeholder="0" value="{{ @old("tTransportasi") }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-form-label" for="basic-default-message">Golongan Karyawan</label>
                        <span class="text-danger pl-1">*</span>
                        <select name="gol_karyawan" id="gol_karyawan" class="form-control" required="required">
                            <option value="">-- Pilih Golongan Karyawan --</option>
                            <option value="Magang" {{ @old('gol_karyawan') == 'Magang' ? "selected" : '' }}>Magang</option>
                            <option value="PKWT" {{ @old('gol_karyawan') == 'PKWT' ? "selected" : '' }}>PKWT</option>
                            <option value="KHL" {{ @old('gol_karyawan') == 'KHL' ? "selected" : '' }}>KHL</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-form-label" for="basic-default-message">Status Marital</label>
                        <span class="text-danger pl-1">*</span>
                        <select name="marital" id="marital" class="form-control" required="required">
                            <option value="">-- Pilih Status Marital --</option>
                            <option value="K0" {{ @old('marital') == 'K0' ? "selected" : '' }}>K0</option>
                            <option value="K1" {{ @old('marital') == 'K1' ? "selected" : '' }}>K1</option>
                            <option value="K2" {{ @old('marital') == 'K2' ? "selected" : '' }}>K2</option>
                            <option value="TK" {{ @old('marital') == 'TK' ? "selected" : '' }}>TK</option>
                        </select>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <!-- /DATA KARYAWAN FORM -->

    </div>

    <div class="row">
        <!-- DOKUMEN PENDUKUNG FORM -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Dokumen Pendukung</h5>
                </div>
                <div class="card-body">
                    <br  />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto KTP</label>

                            <input class="form-control"   name="ktp" type="file" id="ktp">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor KTP</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="n_ktp" type="number" id="n_ktp" value="{{ @old("n_ktp") }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto NPWP</label>

                            <input class="form-control"   name="npwp" type="file" id="npwp">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor NPWP</label>

                            <input class="form-control"   name="n_npwp" type="text" id="n_npwp" value="{{ @old("n_npwp") }}" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto KPJ</label>

                            <input class="form-control"   name="kpj" type="file" id="kpj">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor KPJ</label>

                            <input class="form-control"   name="n_kpj" type="text" id="n_kpj" value="{{ @old("n_kpj") }}" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload foto JKN</label>
                            <input class="form-control"   name="jkn" type="file" id="jkn">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomor JKN</label>
                            <input class="form-control"   name="n_jkn" type="text" id="n_jkn" value="{{ @old("n_jkn") }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload Riwayat Hidup</label>
                            <input class="form-control"   name="cv" type="file" id="cv">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Upload Dokumen Lainnya</label>
                            <input class="form-control"   name="lainnya" type="file" id="lainnya">
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <!-- /DOKUMEN PENDUKUNG FORM -->


        <!-- DATA BANK FORM -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Detail Akun Bank</h5>
                </div>
                <div class="card-body">
                    <br   />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nama Pemilik Rekening</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="nama_pemegang_bank" type="text" id="nama_pemegang_bank" value="{{ @old("nama_pemegang_bank") }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nomer Rekening</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="nomer_rek_bank" type="number" id="nomer_rek_bank" value="{{ @old("nomer_rek_bank") }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nama Bank</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="nama_bank" id="pilih_bank" class="form-control" required>
                                <option value="" >-- Pilih Bank --</option>
                                @foreach ($bank as $item)
                                    <option value="{{ $item->nama_bank }}" {{ @old('nama_bank') == $item->nama_bank ? 'selected' : '' }}>{{ $item->nama_bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Kode Bank</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="kd_bank" type="number" id="kd_bank" value="{{ @old("kd_bank") }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class=" col-form-label" for="basic-default-name">Lokasi Cabang </label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"   name="bank_cabang" type="text" id="bank_cabang" value=" {{ @old("bank_cabang") }}" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <button class="btn btn-primary" type="submit" style="float: right;" class=" form-control"  > Simpan Data</button>
                        </div>
                    </div>

                    <br>
                </div>
            </div>
        </div>
        <!-- DATA BANK FORM -->
    </div>
</form>
@endsection

@push('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    var url_select_bank = "{{ route('cari-bank') }}";
</script>
   <script src="{{ asset('assets/js/superadmin/add_karyawan.js') }}"></script>
@endpush
