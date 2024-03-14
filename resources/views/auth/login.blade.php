<!DOCTYPE html>

<!-- beautify ignore:start -->
<html  lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>Login | SmartHRM - PFI</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asseT('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body" >
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" alt="" width="90px">
                            </span>
                            {{-- <span class="app-brand-text  text-body fw-bolder">Smart HRM</span> --}}
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2" style="color: black;">Selamat Datang di Smart HRM!</h4>
                        <br><br>
                        <div class="mb-3" id="kembali">
                            <button onclick="roles('kembali')" class="btn btn-primary btn-sm">kembali</button>
                        </div>
                        <div class="select_akun" id="select_akun" style="text-align:center;">
                            <div class="mb-3">
                                <label for="email" class="form-label">Pilih Tipe akun anda</label>
                            </div>
                            <button onclick="roles('admin')" class="btn btn-primary btn-sm">Direktur / Supervisor / Admin</button>
                            <button onclick="roles('karyawan')" class="btn btn-primary btn-sm">Karyawan</button>
                        </div>
                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST"  >
                            @csrf
                            <input type="hidden" class="form-control" id="roles" name="roles" value="" />
                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label>
                                <input type="text" class="form-control" id="email" name="username" placeholder="Masukan Username Anda" autofocus/>
                            </div>
                            <div class="mb-3 form-password-toggle">

                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" id="login_button" type="submit">Masuk</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $("#formAuthentication").hide();
        $("#kembali").hide()

        var home = "{{ route('home') }}";
    </script>

    <script>
        function roles(val) {
           $("#roles").val(val);
            if(val == 'kembali') {
                $("#formAuthentication").hide(300);
                $("#select_akun").show(300);
                $("#kembali").hide(300);
            }else {
                $("#formAuthentication").show(300);
                $("#select_akun").hide(300);
                $("#kembali").show(300)

            }

        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
  </body>
</html>
