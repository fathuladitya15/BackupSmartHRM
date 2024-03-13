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
                    <input class="form-control"   name="tglLhr"  value="{{ $kr->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki - Laki' }}"  type="text" id="tglLhr" readonly>
                </div>
                <div class="form-group col-md-6">
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
                    <label class="col-form-label" for="">No Kartu Keluarga</label>
                    <input type="text" name="no_kk" id="no_kk" class="form-control" value="{{ $kr->no_kk }}"   placeholder="Masukan Jumlah Anak" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label" for="">Nama Ibu</label><span class="text-danger pl-1">*</span>
                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ $kr->nama_ibu }}"  class="form-control"  placeholder="Masukan Nama Ibu" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
