<!DOCTYPE html>

<!-- beautify ignore:start -->
<html lang="en" class="light-style layout-compact layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" >
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title> @yield('title') | SmartHRM</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/layouts/logo-pfi-dark.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    @stack('css')
    <style>
        #sig {
            width: 200px;
            height: 200px;
            border: 1px solid #ccc;
        }
    </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        @include('layouts.partials.sidebar')

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

            @include('layouts.partials.navbar')

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
                @yield('content')

                <div class="modal fade" id="modalTTD" role="dialog"  data-bs-backdrop="static" >
                    <div class="modal-dialog " role="document" >
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="" style="color: black">Buat Tanda Tangan </h5>
                            <button type="button" class="btn btn-default close-ttd" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form id="formTandaTangan">
                                <div class="modal-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="" class="col-form-label">Tanda Tangan disini</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="text-align: center;">
                                            <div id="sig" class="form-control"></div>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row">
                                        <p><label for="">*) Mohon pastikan anda membuat tanda tangan di tengah kotak</label></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="javascript:void(0)"  id="clear" class="btn btn-danger">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , PT PROVEN FORCE INDONESIA
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="http://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script type="module" src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js" defer></script>
    <script type="module"  src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging.js" defer></script> --}}
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('js')

    <script>
        var sig         = $('#sig').signature(
                {
                    syncField: '#signature',
                    syncFormat: 'PNG' ,
                    bgColour: 'transparent'
                }
            );
        var url_cek_ttd = "{{ route('check-tanda-tangan') }}";
        var url_save_ttd= "{{ route('save-tanda-tangan') }}";
        var assets      = "{{ asset('') }}";

        function ttd(id_karyawan) {
            Swal.fire({
                title: "Anda Yakin ?",
                text: "Anda Akan Menandatangani Surat ini !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Tanda Tangan !"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url_cek_ttd,
                        data: {id_karyawan : id_karyawan},
                        type: "POST",
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
                        },success: function(s) {
                            if(s.status == true) {
                                Swal.fire({
                                    title: s.title,
                                    text: s.pesan,
                                    icon: "success"
                                });
                                document.getElementById("image_ttd").src = assets + s.path;
                                $("#ttd").val(1);
                                document.getElementById("button_ttd").style.display = "none";
                            }else {
                                $("#modalTTD").modal("show");
                                Swal.close()
                            }

                        }, error : function(e) {
                            console.log(e)
                            Swal.fire({
                                title: "Terjadi kesalahan",
                                text: "Hubungi tim IT",
                                icon: "error"
                            });

                        },complete: function() {
                            Swal.hideLoading();
                        }
                    })

                }
            });
        }

        $("#formTandaTangan").submit(function(e) {
            var svg         = sig.signature('toDataURL');
            console.log(sig);
            e.preventDefault();
            $.ajax({
                url: url_save_ttd,
                type: "POST",
                data : {svg : svg},
                beforeSend: function() {
                    Swal.fire({
                        title: "Sedang menyimpan tanda tangan anda!",
                        html: "Jangan Tinggalkan Halaman ini <b></b> ",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                },success : function(s) {
                    if(s.status == true) {
                        Swal.fire({
                            title: s.title,
                            text: s.pesan,
                            icon: "success"
                        });
                        document.getElementById("image_ttd").src = assets + s.file;
                        $("#ttd").val(1);
                    }
                },error: function(e) {
                    // console.log(e);
                    Swal.fire({
                        title: "Terjadi kesalahan",
                        text: "Hubungi tim IT",
                        icon: "error"
                    });
                },complete: function() {
                    $('#modalTTD').modal('hide');
                    document.getElementById("button_ttd").style.display = "none";
                }
            })
        })
        $(document).on('click','#clear', function() {
            $('#sig').signature('clear');

        })


    </script>

    <script>


        // function initializeFirebase() {

            const firebaseConfig = {

                apiKey: 'AIzaSyA_T_z7YTOWgcVqgGZYCQWcySxNtgkOsKw',
                authDomain: 'pushnotification-52987.firebaseapp.com',
                projectId: 'pushnotification-52987',
                storageBucket: 'pushnotification-52987.appspot.com',
                messagingSenderId: '773466760609',
                appId: '1:773466760609:web:6c689b798f25081ac157e0',
                measurementId: 'G-NYL0QLL715',
            };

            // Inisialisasi Firebase
            firebase.initializeApp(firebaseConfig);

            // Inisialisasi Firebase Messaging
            const messaging = firebase.messaging();
            // var pathSW = "{{ asset('assets/js/firebase/firebase-messaging-sw.js') }}";
            // // Mengatur Service Worker
            // if ('serviceWorker' in navigator) {
            //     navigator.serviceWorker.register(pathSW)
            //         .then(registration => {
            //             console.log('Service Worker registered:', registration);

            //             // Mengatur messaging untuk menggunakan Service Worker yang sudah didaftarkan
            //             messaging.useServiceWorker(registration);

            //             // Mendapatkan token
            //             messaging.getToken({ vapidKey: 'YOUR_VAPID_KEY' })
            //                 .then(currentToken => {
            //                     if (currentToken) {
            //                         console.log('Token:', currentToken);
            //                         // Kirim token ke server jika diperlukan
            //                     } else {
            //                         console.log('No registration token available.');
            //                     }
            //                 })
            //                 .catch(error => {
            //                     console.log('An error occurred while retrieving token. ', error);
            //                 });
            //         })
            //         .catch(error => {
            //             console.log('Service Worker registration failed:', error);
            //         });
            // }
        // }
        // Konfigurasi Firebase
        // initializeFirebase();

        function sendNotification() {
            messaging.requestPermission().then(function(value) {
                return messaging.getToken()
            }).then(function(value) {
                $.ajax({
                    url: "{{ route('saveToken') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN' : "{{ csrf_token() }}"
                    },
                    data: {
                        not_token : value
                    },success: function(res) {
                        alert(res)
                    }
                }).catch(function(error) {
                    alert(error);
                })
            })
        }

        // function sendNotification() {
        //     fetch('/send-notification', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         body: JSON.stringify({})
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             alert('Notifikasi berhasil dikirim!');
        //         } else {
        //             alert('Gagal mengirim notifikasi: ' + data.error);
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //     });
        // }
    </script>

  </body>
</html>
