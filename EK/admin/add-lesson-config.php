<?php
include '../db_conn.php';

if(isset($_POST['submit'])){
    $video_name = $_FILES['lesson_video_url']['name'];
    $tmp_name = $_FILES['lesson_video_url']['tmp_name'];
    $error = $_FILES['lesson_video_url']['error'];

    // Get lesson details from the form
    $lesson_name = mysqli_real_escape_string($conn, $_POST['lesson_name']);
    $lesson_quiz = mysqli_real_escape_string($conn, $_POST['lesson_quiz']);

    if (!empty($_FILES['lesson_image_url']['name'])) {
        $lesson_image_name = $_FILES['lesson_image_url']['name'];
        $lesson_image_tmp_name = $_FILES['lesson_image_url']['tmp_name'];
        $lesson_image_size = $_FILES['lesson_image_url']['size'];
        $lesson_image_type = $_FILES['lesson_image_url']['type'];
        $lesson_image_ext = strtolower(pathinfo($lesson_image_name, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowed_ext = array("jpg", "jpeg", "png", "gif");

        // Check if file format is allowed
        if (in_array($lesson_image_ext, $allowed_ext)) {
            // Check if file size is less than 5MB
            if ($lesson_image_size < 5000000) {
                // Generate a unique filename for the avatar
                $lesson_image_new_name = uniqid('', true) . '.' . $lesson_image_ext;
                $lesson_image_upload_path = 'uploadsImageLesson/' . $lesson_image_new_name;

                // Move uploaded file to designated folder
                move_uploaded_file($lesson_image_tmp_name, $lesson_image_upload_path);

                // Delete previous avatar if it exists
                if (!empty($lesson_image_url)) {
                    unlink('uploadsImageLesson/' . $lesson_image_url);
                }

                
            } else {
                // File size is too large
                echo "<p>File size too large. Maximum file size allowed is 5MB.</p>";
            }
        } else {
            // Invalid file format
            echo "<p>Invalid file format. Only JPG, JPEG, PNG and GIF files are allowed.</p>";
        }
    }
    $video_ex = pathinfo($video_name, PATHINFO_EXTENSION);
    $video_ex_lc = strtolower($video_ex);
    $allowed_exs = array("mp4", "webm", "avi", "flv");

    if(in_array($video_ex_lc, $allowed_exs)) {
        $new_video_name = uniqid("video-", true) . "." . $video_ex_lc;
        $video_upload_path = 'uploads/' . $new_video_name;
        move_uploaded_file($tmp_name, $video_upload_path);

    }

    // Insert lesson into database
    $sql = "INSERT INTO `lesson_db` (lesson_name, lesson_image_url, lesson_quiz, lesson_video_url) 
            VALUES ('$lesson_name', '$lesson_image_new_name', '$lesson_quiz', '$new_video_name')";

    if ($conn->query($sql) === TRUE) {
        //echo "<script>alert('Lesson added successfully!')</script>";
        //show image has been added
        
        
        $lesson_id = $conn->insert_id;

        // Flashcards
        $front_flashcards = $_FILES['lesson_front_flashcard']['name'];
        $front_flashcards_tmp = $_FILES['lesson_front_flashcard']['tmp_name'];
        $back_flashcards = $_POST['lesson_back_flashcard'];
        if (!empty($front_flashcards)) {
            for ($i = 0; $i < count($front_flashcards); $i++) {
                $front_flashcard_name = $front_flashcards[$i];
                $front_flashcard_tmp = $front_flashcards_tmp[$i];
                $back_flashcard_text = mysqli_real_escape_string($conn, $back_flashcards[$i]);
        
                $front_flashcard_ext = strtolower(pathinfo($front_flashcard_name, PATHINFO_EXTENSION));
        
                $allowed_ext = array("jpg", "jpeg", "png", "gif");
        
                if (in_array($front_flashcard_ext, $allowed_ext)) {
                    if ($_FILES['lesson_front_flashcard']['size'][$i] < 5000000) {
                        $front_flashcard_new_name = uniqid('', true) . '.' . $front_flashcard_ext;
                        $front_flashcard_upload_path = 'flashcards/' . $front_flashcard_new_name;
        
                        move_uploaded_file($front_flashcard_tmp, $front_flashcard_upload_path);
        
                        $sql_flashcard = "INSERT INTO `flashcard_db` (lesson_id, front_flashcard_url, back_flashcard_text) 
                                          VALUES ('$lesson_id', '$front_flashcard_new_name', '$back_flashcard_text')";
        
                        if ($conn->query($sql_flashcard) !== TRUE) {
                            echo "Error: " . $sql_flashcard . "<br>" . $conn->error;
                        }
                    } else {
                        echo "<p>File size too large. Maximum file size allowed is 5MB.</p>";
                    }
                } else {
                    echo "<p>Invalid file format. Only JPG, JPEG, PNG and GIF files are allowed.</p>";
                }
            }
        }

        header("location: manage-posts.php");

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    
    
    

}
    

?>
