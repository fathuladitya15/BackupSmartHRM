<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Profile Details</h5>
            <div class="card-body">
                <form action="{{ route('karyawan-update',['hash' => HashVariable($data_karyawan->id_karyawan)]) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" name="id_users" value="{{ $data_users->id }}">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{ foto_profile($data_karyawan->id_karyawan) }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>
                        <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Unggah Foto Profile Baru</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input type="file" id="upload" class="account-file-input" name="foto" hidden accept="image/png, image/jpeg" />
                        </label>
                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>

                        <p class="text-muted mb-0">Gambar yang diizinkan JPG or PNG. Max size of 1 MB</p>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="form-group">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="" class="form-label">Username</label>
                                <input class="form-control" type="text" id="username" name="username" value="{{ $data_users->username }}" autofocus/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="" class="form-label">Password</label>
                                <input class="form-control" type="text" id="password" name="password" value="" placeholder="*********"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="gender" class="col-form-label">Tipe Akun</label><span class="text-danger pl-1">*</span>
                                <input type="text" name="tipe_akun" id="tipe_akun" class="form-control" value="{{ $data_users->roles  }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gender" class="col-form-label">Email</label><span class="text-danger pl-1">*</span>
                                <input type="text" name="email" id="email" class="form-control" value="{{ $data_users->email  }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2" style="float: right">Simpan</button>
                              </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Nonaktifkan Akun</h5>
            <div class="card-body">
                <div class="mb-3 col-12 mb-0">
                    <div class="alert alert-warning">
                    <h6 class="alert-heading fw-bold mb-1">Anda yakin ingin menonaktifkan akun ini?</h6>
                    <p class="mb-0">Setelah Anda menonaktifkan akun ini, Akun ini tidak bisa dipakai oleh karyawan. Harap yakin.</p>
                    </div>
                </div>
                <form id="formAccountDeactivation" onsubmit="return false">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">Saya mengkonfirmasi menonaktifkan akun ini</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account">Nonaktif</button>
                </form>
            </div>
        </div>
    </div>
</div>
