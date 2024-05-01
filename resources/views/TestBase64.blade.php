@extends('vBase')
@section('title'){{ 'tes' }}@endsection
@section('content')
<form id="testBASE64" enctype="multipart/form-data">
    <input type="file" name="image_file" accept="image/*">
    <button type="submit">Upload</button>
</form>

@endsection

@push('js')
<script>

    $("#testBASE64").submit(function(e) {
        e.preventDefault();
        var fileInput = document.querySelector('input[type="file"]');
        var file = fileInput.files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
            var base64Image = event.target.result;

            // Kirim data gambar dalam format Base64 ke server
            // sendBase64ToServer(base64Image);
        };
        reader.readAsDataURL(file);
            console.log(reader);
        })
    </script>
@endpush
