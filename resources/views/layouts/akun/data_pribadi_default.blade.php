<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between" >
                <h5 class="mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan-update',['hash' => HashVariable($data_karyawan->id_karyawan)]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_table_karyawan" value="{{ $data_karyawan->id }}">
                    <input type="hidden" name="type" value="data-diri">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Nama</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control " required="required" placeholder="Nama Lengkap Karyawan" name="name" type="text" id="name" value="{{ $data_karyawan->nama_karyawan }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-name">No. Handphone</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="08xxxxxxxxxx" name="nohp" type="number" id="nohp" value="{{ $data_karyawan->no_hp }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tempat Lahir</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="TmptLhr" type="text" id="TmptLhr" placeholder="Jakarta" value="{{ $data_karyawan->tempat_lahir }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Lahir</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required"  name="tglLhr" type="date" id="tglLhr" value="{{ $data_karyawan->tanggal_lahir }}">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="gender" class="col-form-label">Jenis Kelamin</label><span class="text-danger pl-1">*</span>
                            <select name="jk" id="jk" class="form-control" required="required">
                                <option value="">-- Pilih Jenis Kelamin -- </option>
                                <option value="L" {{ $data_karyawan->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki - Laki</option>
                                <option value="P" {{ $data_karyawan->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-form-label" for="alamat">Alamat Ktp</label>
                        <span class="text-danger pl-1">*</span>
                        <textarea  id="alamat" required="required" rows="3" cols="50"  name="alamat"  class="form-control">{{{ $data_karyawan->alamat }}}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-form-label" for="alamat_domisili">Alamat Domisili</label>
                        <span class="text-danger pl-1">*</span>
                        <textarea  id="alamat_domisili" required="required" rows="4" cols="50" name="alamat_domisili"  class="form-control">{{ $data_karyawan->alamat_domisili  }}</textarea>
                    </div>
                    <div class="row">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2" style="float: right">Simpan</button>
                          </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
