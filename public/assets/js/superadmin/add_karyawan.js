
$('#pilih_bank').on('change', function() {
    var nama_bank = $(this).val();
    $.ajax({
        url:url_select_bank,
        type: "POST",
        data: {nama_bank : nama_bank},
        success: function(e) {
            console.log(e.pesan)
            if (e.status == true) {
                var sandi_bank = e.search.sandi_bank;
                $('#kd_bank').val(sandi_bank);
            }else {
                alert(e.pesan)
            }

        }, error: function(s) {
            console.log(s)
        }
    })
})

profile_pictures.onchange = evt => {
    const [file] = profile_pictures.files
    if (file) {
        preview.src = URL.createObjectURL(file)
    }
}
