<!-- resources/views/camera.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Camera Access</title>
</head>
<body>
    <video id="video" width="640" height="480" autoplay></video>
    <button id="snap">Snap Photo</button>
    <canvas id="canvas" width="640" height="480"></canvas>

    <script>
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                var video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });

        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');
        var video = document.getElementById('video');

        document.getElementById('snap').addEventListener('click', function() {
            context.drawImage(video, 0, 0, 640, 480);
            // Di sinilah Anda dapat mengambil gambar dari kanvas dan mengirimkannya ke server untuk diproses
        });
    </script>
</body>
</html>
