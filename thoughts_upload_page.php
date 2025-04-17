<?php
session_start();
$reg_num = $_SESSION['reg_num'];?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thoughts Upload</title>
    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Using Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f0f0f0;
            padding: 20px;
        }

        .container-custom {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        label,
        input,
        textarea {
            margin-bottom: 10px;
        }

        video {
            margin-top: 10px;
            max-width: 100%;
        }

        #progress-container {
            display: none;
            margin-top: 10px;
        }

        #progress-bar {
            width: 0;
            height: 20px;
            background-color: #007bff;
            border-radius: 5px;
            text-align: center;
            line-height: 20px;
            color: #fff;
        }

        #time-remaining {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body class="container-custom">


    <div class="container-custom">
        <h1>Upload a Thought</h1>
        <!-- Add ID to the form -->
        <div id="progress-container" class="progress">
            <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            <div id="time-remaining"></div>
        </div>
        <form id="upload-form" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" name="reg_num" value="<?php echo $reg_num; ?>">
                <label for="video">Select a video:</label>
                <input type="file" class="form-control-file" id="video" name="video" accept="video/*" required maxlength="2147483648">
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <!-- Use type="button" to prevent form submission -->
            <button type="button" class="btn btn-primary" name="submit" onclick="uploadFile()">Submit</button>
        </form>
        <div class="container-custom">
            <video id="video-preview" width="400" controls></video>
        </div>

        <!-- Progress Bar Container -->

    </div>

    <!-- Include jQuery and Bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function uploadFile() {
            var formData = new FormData(document.getElementById('upload-form'));
            formData.append('submit', 'submit'); // Add this line

            $.ajax({
                type: 'POST',
                url: 'though_upload_process.php',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            var percent = Math.round((e.loaded / e.total) * 100);
                            $('#progress-bar').width(percent + '%').html(percent + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    console.log(response);
                    $('#progress-container').hide();
                },
                error: function(error) {
                    console.error(error);
                    $('#progress-container').hide();
                }
            });

            $('#progress-container').show();
        }
        document.getElementById('video').addEventListener('change', function(event) {
            const videoPreview = document.getElementById('video-preview');
            const file = event.target.files[0];
            const videoURL = URL.createObjectURL(file);
            videoPreview.src = videoURL;
        });
    </script>
</body>

</html>