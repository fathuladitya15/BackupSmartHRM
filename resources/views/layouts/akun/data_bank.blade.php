<div class="row">
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
                        <input class="form-control"   name="nama_pemegang_bank" type="text" id="nama_pemegang_bank" value="{{ $data_karyawan->nama_karyawan }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Nomer Rekening</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="nomer_rek_bank" type="number" id="nomer_rek_bank" value="{{ $data_karyawan->norek_bank }}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Nama Bank</label>
                        <span class="text-danger pl-1">*</span>
                        <select name="nama_bank" id="pilih_bank" class="form-control" required="required">
                            <option value="">-- Pilih Bank --</option>
                            @foreach ($bank as $item)
                                <option value="{{ $item->nama_bank }}" {{ $data_karyawan->nama_bank == $item->nama_bank ? 'selected' : '' }}>{{ $item->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class=" col-form-label" for="basic-default-name">Kode Bank</label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="kd_bank" type="number" id="kd_bank" readonly value="{{ $data_karyawan->kd_bank }}">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label class=" col-form-label" for="basic-default-name">Lokasi Cabang </label>
                        <span class="text-danger pl-1">*</span>
                        <input class="form-control" required="required"  name="bank_cabang" type="text" id="bank_cabang" value="{{ $data_karyawan->cabang_bank }}">
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
</div>
