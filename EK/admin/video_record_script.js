      // Global variables
      let stream; // Media stream object

      // Get user media (camera and microphone)
      navigator.mediaDevices
        .getUserMedia({ video: true, audio: true })
        .then((mediaStream) => {
          // Save the media stream for later use
          stream = mediaStream;

          // Attach the media stream to the video element
          const videoElement = document.getElementById('videoElement');
          videoElement.srcObject = mediaStream;
        })
        .catch((error) => {
          console.error('Error accessing user media:', error);
        });

      // Camera control
      const cameraButton = document.getElementById('cameraButton');
      cameraButton.addEventListener('click', () => {
        const videoTracks = stream.getVideoTracks();
        videoTracks.forEach((track) => {
          track.enabled = !track.enabled;
        });
      });

      // Microphone control
      const microphoneButton = document.getElementById('microphoneButton');
      microphoneButton.addEventListener('click', () => {
        const audioTracks = stream.getAudioTracks();
        audioTracks.forEach((track) => {
          track.enabled = !track.enabled;
        });
      });

      // Screen share control
      const screenShareButton = document.getElementById('screenShareButton');
      const screenShareElement = document.getElementById('screenShareElement');

      let screenStream = null; // To keep track of screen sharing stream

      screenShareButton.addEventListener('click', () => {
        if (!screenStream) {
          navigator.mediaDevices
            .getDisplayMedia({ video: true })
            .then((stream) => {
              screenStream = stream;
              const combinedStream = new MediaStream([
                ...stream.getTracks(),
                ...stream.getAudioTracks(), // Include audio if needed
              ]);
              screenShareElement.srcObject = combinedStream;
            })
            .catch((error) => {
              console.error('Error accessing screen media:', error);
            });
        } else {
          // Stop screen sharing
          const tracks = screenStream.getTracks();
          tracks.forEach((track) => track.stop());
          screenStream = null;
          screenShareElement.srcObject = null;
        }
      });

      

    // record video function:
    
    