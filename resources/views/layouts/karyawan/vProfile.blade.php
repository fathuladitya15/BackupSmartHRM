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

    /* The message box */
    #password-message {
        display: none;
        background: #ffffff;
        color: #000;
        position: relative;
        padding: 20px;
        margin-top: 10px;
        text-align: left;
    }

    #password-message h3 {
        font-size: 15px;
        margin-top: 0;
        text-transform: uppercase;
    }

    #password-message p {
        margin: 8px 0;
    }

    .valid {
        color: green;
    }

    .valid:before {
        position: relative;
        left: -7px;
        content: "✔";
    }

    .invalid {
        color: red;
    }

    .invalid:before {
        position: relative;
        left: -7px;
        content: "✖";
    }

    /* Error message style */
    .error-msg {
        color: red;
        font-size: 14px;
        margin-top: 4px;
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
              <a class="nav-link {{ menuActive('profile') }}" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Akun</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('profile-dokumen') }}"><i class='bx bxs-file-blank' ></i> Dokumen</a>
            </li>
          </ul>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between" >
        <h5 class="mb-0">Pengaturan Akun </h5>
    </div>
    <br>
    <div class="card-body">
        <form id="update_password" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}" id="id_user">
            <div class="row">
                <div class="form-group col-md-6" style="text-align: center">
                    <img src="{{ $foto_profile }}" alt="Gambar" id="preview"  class="circular_image" style="align-content: center">
                </div>
                <div class="form-group col-md-6">
                    <label class=" col-form-label" for="basic-default-name">Upload Foto</label>
                    <input class="form-control " placeholder="" name="profile_pictures" type="file" id="profile_pictures">

                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="" class="col-form-label">Password</label>
                    <input type="password" name="password"  class="form-control" id="password" value=""  placeholder="***" >
                </div>
                <div class="form-group col-md-6">
                    <label for="" class="col-form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="confirm_password" value="" class="form-control" placeholder="***" >
                    <p id="info" class="invalid" style="display:none">Password tidak sesuai</p>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-md-6">

                    <div id="password-message"  >

                        <h3>Password harus :</h3>

                        <p class="password-message-item invalid">Mempunyai
                            <b>1 Huruf kecil</b>
                        </p>
                        <p class="password-message-item invalid">Mempunyai
                            <b>1 huruf besar</b>
                        </p>
                        <p class="password-message-item invalid">Mempunyai
                            <b>1 nomor</b>
                        </p>
                        <p class="password-message-item invalid">Mempunyai
                            <b>1 karakter special</b>
                        </p>
                        <p class="password-message-item invalid">Minimum
                            <b>8 karakter</b>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="aksi">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- <div class="row">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-lg-2">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                </div>
                <div class="form-group col-lg-4">
                    <div class="name"><h3>{{ Str::title(Auth::user()->name) }}</h3></div>
                    <div class="divisi"><p class="black">Admin</p></div>
                </div>
                <div class="form-group col-lg-6">
                    Details
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
@push('js')
<script>

    // script.js File
    var passwordInput = document.getElementById("password");
    var passwordMessageItems = document.getElementsByClassName("password-message-item");
    var passwordMessage = document.getElementById("password-message");

    passwordInput.onfocus = function () {
        passwordMessage.style.display = "block";
    }
    // After clicking outside of password input hide the message
    passwordInput.onblur = function () {
        passwordMessage.style.display = "none";
    }

    passwordInput.onkeyup = function () {
        // checking uppercase letters

        let uppercaseRegex = /[A-Z]/g;
        if (passwordInput.value.match(uppercaseRegex)) {
            passwordMessageItems[1].classList.remove("invalid");
            passwordMessageItems[1].classList.add("valid");
        } else {
            passwordMessageItems[1].classList.remove("valid");
            passwordMessageItems[1].classList.add("invalid");
        }
        // console.log(passwordInput.value.match(uppercaseRegex));

        // checking lowercase letters
        let lowercaseRegex = /[a-z]/g;
        if (passwordInput.value.match(lowercaseRegex)) {
            passwordMessageItems[0].classList.remove("invalid");
            passwordMessageItems[0].classList.add("valid");
        } else {
            passwordMessageItems[0].classList.remove("valid");
            passwordMessageItems[0].classList.add("invalid");
        }

        // checking the number
        let numbersRegex = /[0-9]/g;
        if (passwordInput.value.match(numbersRegex)) {
            passwordMessageItems[2].classList.remove("invalid");
            passwordMessageItems[2].classList.add("valid");
        } else {
            passwordMessageItems[2].classList.remove("valid");
            passwordMessageItems[2].classList.add("invalid");
        }

         // checking the special characters
        let specialRegex =/[\!\@\#\$\%\^\&\*\)\(\+\=\.\<\>\{\}\[\]\:\;\'\"\|\~\`\_\-]/g;
        if (passwordInput.value.match(specialRegex)) {
            passwordMessageItems[3].classList.remove("invalid");
            passwordMessageItems[3].classList.add("valid");
        } else {
            passwordMessageItems[3].classList.remove("valid");
            passwordMessageItems[3].classList.add("invalid");
        }

        // Checking length of the password
        if (passwordInput.value.length >= 8) {
            passwordMessageItems[4].classList.remove("invalid");
            passwordMessageItems[4].classList.add("valid");
        } else {
            passwordMessageItems[4].classList.remove("valid");
            passwordMessageItems[4].classList.add("invalid");
        }
    }
</script>
<script>
    var pwdConfirm  = document.getElementById("confirm_password").value;
    var alert       = document.getElementById("info");
    var pwd         = document.getElementById("password");
    var url         = "{{ route('update_password') }}";
    $("#update_password").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({

            url : url,
            data: formData,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                Swal.fire({
                    title: "Mohon Tunggu ... !",
                    html: "Jangan Tinggalkan Halaman ini <b></b> ",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            },
            success : function(s) {
                console.log(s);
                // alert.style.display = 'block';
                if(s.status == true) {
                    Swal.fire({
                        title: s.title,
                        text: s.pesan,
                        icon: "success"
                    });

                }else if(s.status == false) {
                    alert.classList.add("invalid");
                    alert.classList.remove("valid");
                    alert.innerHTML = 'Password Tidak Sesuai';
                }else {
                    $.each(s, function(index,value){
                        Swal.fire({
                            title: "Ops !",
                            text: value,
                            icon: "warning"
                        });
                    })
                }
            },error : function(s) {
                console.log(s);
            },complete: function() {
                setInterval(window.location.reload(),2000);
            }
        })
    })

    profile_pictures.onchange = evt => {
    const [file] = profile_pictures.files
    if (file) {
        preview.src = URL.createObjectURL(file)
    }
}
</script>
@endpush

