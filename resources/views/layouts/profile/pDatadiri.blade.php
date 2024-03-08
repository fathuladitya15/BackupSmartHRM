<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" >
        <h5 class="mb-0">Data Pribadi Anda </h5>
    </div>
    <div class="card-body">
        <div class="card-body">
            <br>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class=" col-form-label" for="basic-default-name">Nama Lengkap</label>
                    <span class="text-danger pl-1">*</span>
                    <input class="form-control "  placeholder="Nama Lengkap Karyawan"  value="{{ Auth::user()->name }}" name="name" type="text" id="name" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="basic-default-name">No. Handphone</label>
                    <span class="text-danger pl-1">*</span>
                    <input class="form-control"  placeholder="08xxxxxxxxxx"  value="{{ $kr->no_hp }}" name="nohp" type="number" id="nohp" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class=" col-form-label" for="basic-default-name">Tempat Lahir</label>
                    <span class="text-danger pl-1">*</span>
                    <input class="form-control"   name="TmptLhr"  value="{{ $kr->tempat_lahir }}" type="text" id="TmptLhr" placeholder="Jakarta" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class=" col-form-label" for="basic-default-name">Tanggal Lahir</label>
                    <span class="text-danger pl-1">*</span>
                    <input class="form-control"   name="tglLhr"  value="{{ $kr->tanggal_lahir }}"  type="date" id="tglLhr" readonly>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="gender" class="col-form-label">Jenis Kelamin</label><span class="text-danger pl-1">*</span>
                    <input class="form-control"   name="tglLhr"  value="{{ $kr->jenis_kelamin }}"  type="date" id="tglLhr" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="gender" class="col-form-label">Pendidikan</label><span class="text-danger pl-1">*</span>
                    <input class="form-control"   name="tglLhr"  value="{{ $kr->pendidikan }}"  type="date" id="tglLhr" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class=" col-form-label" for="basic-default-name">Email</label>
                    <span class="text-danger pl-1">*</span>
                    <input class="form-control"  placeholder="Email@mail.com" value="{{Auth::user()->email }}" name="email" type="email" id="email" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="alamat">Alamat Ktp</label>
                    <span class="text-danger pl-1">*</span>
                    <textarea  id="alamat"  rows="1" cols="10"  name="alamat"   class="form-control" readonly>{{{ $kr->alamat }}}</textarea>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="alamat_domisili">Alamat Domisili</label>
                    <span class="text-danger pl-1">*</span>
                    <textarea  id="alamat_domisili"  rows="1" cols="50" name="alamat_domisili"  readonly class="form-control">{{{ $kr->alama_domisili }}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="alamat">Usia</label><span class="text-danger pl-1">*</span>
                    <input type="text" name="usia" id="usia" class="form-control" value="{{ @old('usia') }}"  placeholder="Masukan Usia" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="alamat">Agama</label><span class="text-danger pl-1">*</span>
                    <input class="form-control"   name="tglLhr"  value="{{ $kr->agama }}"  type="date" id="tglLhr" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="">Nama Suami/Istri</label>
                    <input type="text" name="nama_suami_istri" value="{{ $kr->nama_s_i }}"  id="nama_suami_istri" class="form-control"  placeholder="Masukan Nama Suami/Istri" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="">Nama Anak</label>
                    <input type="text" name="nama_anak" id="nama_anak" class="form-control"  placeholder="Nama Anak 1, Nama Anak 2, dst" value="{{ $kr->nama_anak }}"  readonly>
                    <p style="font-size: 70%;">*) Gunakan ( , / koma ) apabila nama anak lebih dari 1</p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="">Jumlah Anak</label>
                    <input type="text" name="jumlah_anak" id="jumlah_anak" class="form-control" value="{{ $kr->jmlh_anak }}"   placeholder="Masukan Jumlah Anak" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="">Nama Ibu</label><span class="text-danger pl-1">*</span>
                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ $kr->nama_ibu }}"  class="form-control"  placeholder="Masukan Nama Ibu" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="col-form-label" for="">Nama Bapak</label><span class="text-danger pl-1">*</span>
                    <input type="text" name="nama_bapak" id="nama_bapak" class="form-control" value="{{ $kr->nama_bapa }}"  placeholder="Nama Bapak" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
