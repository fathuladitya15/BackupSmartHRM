const button_login = document.getElementById("login_button");
const loading   = 'Mohon Tunggu &nbsp;<div class="spinner-border spinner-border-sm text-default" role="status"><span class="visually-hidden">Loading...</span></div> ';

$('#formAuthentication').submit(function(e) {
    e.preventDefault();
    var username = $("#email").val();
    var password = $("#password").val();
    if(username == "" && password == "" ) {
        Swal.fire({
            text: "Username / Password Wajib diisi !",
            title: "",
            icon: "info"
          });
    }else {
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: "POST",
            beforeSend:function() {
                button_login.innerHTML = loading;
                button_login.disabled - true
            },
            success : function(e) {
                console.log(e);
                if(e.status == false) {
                    Swal.fire({
                        title: "Maaf ! ",
                        text: e.messages,
                        icon: "error"
                      });
                }
                // else {
                //     let timerInterval;
                //     Swal.fire({
                //       title: "Login Berhasil !",
                //       html: "Anda Akan Dialihkan Ke Menu Beranda ",
                //       icon: "success",
                //       timer: 2000,
                //       timerProgressBar: true,
                //       didOpen: () => {
                //         Swal.showLoading();
                //         const timer = Swal.getPopup().querySelector("b");
                //         timerInterval = setInterval(() => {
                //           timer.textContent = `${Swal.getTimerLeft()}`;
                //         }, 100);
                //       },
                //       willClose: () => {
                //         clearInterval(timerInterval);
                //       }
                //     }).then((result) => {
                //       /* Read more about handling dismissals below */
                //       if (result.dismiss === Swal.DismissReason.timer) {
                //         // console.log("I was closed by the timer");
                //         window.location.href = home;
                //       }
                //     });
                // }
            },error: function(e) {
                Swal.fire({
                    title: "Opps !",
                    text: e.responseJSON.message,
                    icon: "error"
                  });
            }, complete: function() {
                button_login.innerHTML = "Login";
                button_login.disabled  = false;
            }
        })

    }
})
