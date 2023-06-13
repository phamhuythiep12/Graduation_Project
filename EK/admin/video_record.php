<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <video id="videoElement" autoplay></video>
    <video id="screenShareElement" autoplay></video>

    <div>
        <button id="cameraButton">Open/Close Camera</button>
        <button id="screenShareButton">Share Screen/ Stop Sharing</button>
        <button id="microphoneButton">Mute/ Unmute</button>
        <button id="btn-start-recording">Start Recording</button>
        <button id="btn-stop-recording" disabled="disabled">Stop Recording</button>
    </div>

    <video id="my-preview" controls autoplay></video>

    <script src="video_record_script.js"></script>

    <script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
</body>
</html>
