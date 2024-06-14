@push('css')
<style>

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
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between" >
        <h5 class="mb-0">Notifikasi </h5>
    </div>
    <div class="card-body">
        <div class="mb-3 col-12 mb-0">
          <div class="alert alert-warning">
            <h6 class="alert-heading fw-bold mb-1">Perizinan Notifikasi </h6>
            <p class="mb-0">Klik tombol <b>Tes Notifikasi </b></p>
          </div>
        </div>
        <form id="formAccountDeactivation">

          <button type="button" id="notifikasi" onclick="startFCM()"  class="btn btn-danger deactivate-account">Test Notifikasi</button>
        </form>
      </div>
</div>

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
    function startFCM() {
        messaging.requestPermission().then(function () {
                return messaging.getToken()
            })
            .then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("store.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        console.log(response);
                        // console.log('Token stored.');
                    },
                    error: function (error) {
                        console.log(error);
                    },
                });
            }).catch(function (error) {
                console.log(error);
            });
    }
    // $(document).on('click','#notifikasi', function() {
    //     console.log("TES");

    // })

</script>
@endpush
