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
                            <input class="form-control"  placeholder="Masukan ID Card" name="karyawan_id" value="{{ Auth::user()->id_karyawan }}"  type="text" id="karyawan_id" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="">Kategori</label>
                            <span class="text-danger pl-1">*</span>
                            <input class="form-control"  placeholder="" name="kategori" value="{{ $kr->kategori }}"  type="text" id="kategori" readonly>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Lokasi Kerja</label>
                            <span class="text-danger pl-1">*</span>
                            <button type="button" class="btn btn-sm " style="size: 10%"  data-toggle="tooltip" data-placement="top" title="Pilih lokasi kerja sesuai apa yang ditugaskan. Contoh karyawan PT. PFI yang ditugasakan untuk menjadi bagian dari PT. Lain. Jadi lokasi kerjanya yaitu PT.Lain dengan KATEGORI Karyawan Pusat">

                            <i class='bx bxs-info-circle'></i>
                            </button>
                            <input class="form-control"  placeholder="" name="kategori" value="{{ $lokasi_kerja->nama_client }}"  type="text" id="kategori" readonly>

                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Jabatan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" value="{{ $jabatan }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Pilih Divisi</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" value="{{ $divisi }}" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">No.SIO</label>
                            <input type="text" class="form-control" name="noSio" id="noSio" placeholder="-" value="{{ $kr->no_sio }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Bergabung </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control" required="required" name="join_date" id="join_date" value="{{ $kr->join_date }}"  readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Tanggal Selesai </label>
                            <span class="text-danger pl-1">*</span>
                            <input type="date" class="form-control" required="required" name="end_date" id="end_date" value="{{ $kr->end_date }}"  readonly>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <label class=" col-form-label" for="basic-default-name">Tunjangan</label>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Jabatan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" name="tJabatan" id="tJabatan" placeholder="0" value="{{ $kr->tJabatan }}" readonly>
                              </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class=" col-form-label" for="basic-default-name">Transportasi</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" name="tTransportasi" id="tTransportasi" placeholder="0" value="{{ $kr->tTransport }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-message">Golongan Karyawan</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control" value="{{ $kr->gol_karyawan }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="basic-default-message">Status Marital</label>
                            <span class="text-danger pl-1">*</span>
                            <input type="text" class="form-control" value="{{ $kr->marital }}" readonly>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <!-- /DATA KARYAWAN FORM -->
