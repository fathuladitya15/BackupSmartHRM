<div class="row">
    <div class="col-xxl">
        <di class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Detail Perusahaan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan-update',['hash' => HashVariable($data_karyawan->id_karyawan)]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_table_karyawan" value="{{ $data_karyawan->id }}">
                    <input type="hidden" value="data-perusahaan" name="type">
                    <br  />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">ID Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control" required="required" placeholder="Masukan ID Card" name="karyawan_id" type="text" id="karyawan_id" value="{{ $data_karyawan->id_karyawan }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Lokasi Kerja</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="branch" id="branch" class="form-control" required="required">
                                <option value="">-- Pilih Lokasi Kerja --</option>
                                @foreach ($client as $t )
                                    <option value="{{ $t->id }}" {{ $data_karyawan->lokasi_kerja == $t->id ? 'selected' : '' }}>{{ $t->nama_client }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="jabatan" id="jabatan" class="form-control" required="required">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $aj )
                                    <option value="{{ $aj->id }}" {{ $data_karyawan->jabatan == $aj->id ? 'selected' : '' }}>{{ $aj->nama_jabatan }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="divisi" id="divisi" class="form-control" required="required" >
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisi as $dv )
                                    <option value="{{ $dv->id }}" {{ $data_karyawan->divisi == $dv->id ? 'selected' : '' }}>{{ $dv->nama_divisi }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Bergabung </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control" required="required" name="join_date" id="join_date" value="{{$data_karyawan->join_date }}" >
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Selesai </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control" required="required" name="end_date" id="end_date" value="{{ $data_karyawan->end_date }}" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">No.SIO</label>
                            <input type="text" class="form-control" name="noSio" id="noSio" placeholder="-" value="{{ $data_karyawan->no_sio }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">cuti</label>
                            <input type="text" class="form-control" name="cuti" id="cuti" placeholder="-" value="{{ $data_karyawan->cuti }}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class=" col-form-label" for="basic-default-name">Tunjangan</label>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" name="tJabatan" id="tJabatan" placeholder="0" value="{{$data_karyawan->tJabatan }}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Transportasi</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" name="tTransportasi" id="tTransportasi" placeholder="0" value="{{ $data_karyawan->tTransport }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-message">Golongan Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="gol_karyawan" id="gol_karyawan" class="form-control" required="required">
                                <option value="">-- Pilih Golongan Karyawan --</option>
                                <option value="Magang" {{$data_karyawan->gol_karyawan == 'Magang' ? "selected" : '' }}>Magang</option>
                                <option value="PKWT" {{$data_karyawan->gol_karyawan == 'PKWT' ? "selected" : '' }}>PKWT</option>
                                <option value="KHL" {{$data_karyawan->gol_karyawan == 'KHL' ? "selected" : '' }}>KHL</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-message">Status Marital</label>
                            <span class="text-danger pl-1">*</span>
                            <select name="marital" id="marital" class="form-control" required="required">
                                <option value="">-- Pilih Status Marital --</option>
                                <option value="K0" {{ $data_karyawan->marital == 'K0' ? "selected" : '' }}>K0</option>
                                <option value="K1" {{ $data_karyawan->marital == 'K1' ? "selected" : '' }}>K1</option>
                                <option value="K2" {{ $data_karyawan->marital == 'K2' ? "selected" : '' }}>K2</option>
                                <option value="TK" {{ $data_karyawan->marital == 'TK' ? "selected" : '' }}>TK</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2" style="float: right">Simpan</button>
                          </div>
                    </div>
                </form>
            </div>
        </di>
    </div>
</div>
