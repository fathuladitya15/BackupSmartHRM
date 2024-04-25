<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Tanda Tangan Digital</title>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI CDN -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>




    <!-- Keith Wood Signature CDN -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="{{ asset('assets/js/jquery.signature.js') }}"></script>

     <!-- Touch Punch CDN -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        /* Style untuk container tanda tangan */
        #signaturePad {
            width: 200px;
            height: 200px;
            border: 1px solid #ccc;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Tanda Tangan Digital</h1>

    <!-- Form untuk Tanda Tangan -->
    <div id="signaturePad"></div>

    <!-- Tombol untuk Simpan Tanda Tangan -->
    <button id="saveSignature">Simpan Tanda Tangan</button>
    <!-- <input type="text" name="id_karyawan" id="id_karyawan" value= "{{ $id_karyawan }}"> -->

    <!-- Script untuk Inisialisasi Touch Punch dan Keith Wood Signature -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var url_save_ttd= "{{ route('save-ttd-mobile') }}";
            var id_karyawan = "{{ $id_karyawan }}"

            // Inisialisasi Keith Wood Signature
            var sig         = $('#signaturePad').signature({syncField: '#signature', syncFormat: 'PNG'});

            // Inisialisasi Touch Punch
            $('#signaturePad').draggable().css({
                'touch-action': 'none'
            });
            // Simpan Tanda Tangan
            $('#saveSignature').click(function() {
                var svg         = sig.signature('toDataURL');
                $.ajax({
                    url: url_save_ttd,
                    type: "POST",
                    data : {svg : svg, id_karyawan : id_karyawan },
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
                        console.log(s);
                        if(s.status == true) {
                            Swal.fire({
                                title: s.title,
                                text: s.pesan,
                                icon: "success"
                            });
                        }else {
                            Swal.fire({
                                title: s.title,
                                text: s.pesan,
                                icon: "warning"
                            });
                        }
                    },error: function(e) {
                        console.log(e);
                        Swal.fire({
                            title: "Terjadi kesalahan",
                            text: "Hubungi tim IT",
                            icon: "error"
                        });
                    },complete: function() {
                        // $('#modalTTD').modal('hide');
                        // document.getElementById("button_ttd").style.display = "none";
                    }
                })
            });
        });
    </script>
</div>

</body>
</html>
