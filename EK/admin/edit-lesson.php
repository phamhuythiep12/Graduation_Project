<?php
include "../db_conn.php";

$lesson_id = $_GET['update'];
$sql = mysqli_query($conn, "SELECT * FROM `lesson_db` WHERE lesson_id = $lesson_id");

$row = mysqli_fetch_assoc($sql);

if (isset($_POST['submit'])) {
    $lesson_name = mysqli_real_escape_string($conn,$_POST['lesson_name']);
    //$lesson_image_url = $_FILES['lesson_image_url']['name'];
    //$my_video = $_FILES['my_video']['name'];
    $lesson_quiz = mysqli_real_escape_string($conn,$_POST['lesson_quiz']);


    // Update lesson table
    //$update_lesson_query = "UPDATE lesson_db SET lesson_name='$lesson_name', lesson_image_url='$lesson_image_url', lesson_video_url='$my_video', lesson_quiz='$lesson_quiz' WHERE lesson_id=$id";
    //mysqli_query($conn, $update_lesson_query);
/*
    // Update flashcard table
    $flashcard_count = count($_POST['lesson_front_flashcard[]']);
    for ($i = 0; $i < $flashcard_count; $i++) {
        $front_flashcard_url = $_POST['lesson_front_flashcard[]'][$i];
        $back_flashcard_text = $_POST['lesson_back_flashcard[]'][$i];
        $flashcard_id = $_POST['flashcard_id'][$i];

        if ($flashcard_id) {
            // Update existing flashcard
            $update_flashcard_query = "UPDATE flashcard_db SET front_flashcard_url='$front_flashcard_url', back_flashcard_text='$back_flashcard_text' WHERE id=$flashcard_id";
            mysqli_query($conn, $update_flashcard_query);
            
        } else {
            // Insert new flashcard
            $insert_flashcard_query = "INSERT INTO flashcard_db (lesson_id, front_flashcard_url, back_flashcard_text) VALUES ('$lesson_id', '$front_flashcard_url', '$back_flashcard_text')";
            mysqli_query($conn, $insert_flashcard_query);
        }
    }
*/
    // Check if admin upload a new lesson image
    if(!empty($_FILES['lesson_image_url']['name']))
    {
        $image_name = $_FILES['lesson_image_url']['name'];
        $image_tmp_name = $_FILES['lesson_image_url']['tmp_name'];
        $image_size = $_FILES['lesson_image_url']['size'];
        $image_type = $_FILES['lesson_image_url']['type'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        $allowed_ext = array("jpg", "jpeg", "png", "gif");

        //Check if file format is allowed
        if(in_array($image_ext, $allowed_ext)){
            if($image_size < 5000000){
                //Generate a unique filename for the image lesson
                $image_new_name = uniqid('', true) . '.' . $image_ext;
                $image_upload_path = 'uploadsImageLesson/' . $image_new_name;

                //Move uploaded file to designated folder
                move_uploaded_file($image_tmp_name, $image_upload_path);

                //Delete previous image if it exist
                if(!empty($image_lesson)){
                    unlink('uploadsImageLesson/' . $image_lesson);
                }

                //Update lesson_image in database
                $sql_update_image = "UPDATE lesson_db SET lesson_image_url = '$image_new_name' WHERE lesson_id = '$lesson_id'";
                $result_update_image = mysqli_query($conn, $sql_update_image);
            }else{
                //File size is too large
                echo "<p> File is too large. Maximum file size allowed is 5MB. </p>";

            }
        }else{
            echo "<p> File format is not allowed. </p>";
        }
    }

    // Upload lesson video
    

    if(!empty($_FILES['lesson_video_url']['name']))
    {
        $video_name = $_FILES['lesson_video_url']['name'];
        $video_tmp_name = $_FILES['lesson_video_url']['tmp_name'];

        if($error === 0){
            $video_ex = strtolower(pathinfo($video_name, PATHINFO_EXTENSION));

            $video_ex_lc = strtolower($video_ex);
            $allowed_exs = array("mp4", "webm", "avi", "flv");

            if(in_array($video_ex_lc, $allowed_exs))
            {
                $new_video_name = uniqid("", true). "." .$video_ex;

                $video_upload_path = 'uploads/' .$new_video_name; 

                //Move uploaded file to designated folder
                move_uploaded_file($video_tmp_name, $video_upload_path);

                if(!empty($lesson_video_url)){
                    unlink('uploads/'. $lesson_video_url);
                }

                //Update video link indatabase
                $sql_update_video = "UPDATE lesson_db SET lesson_video_url = '$new_video_name' WHERE lesson_id = '$lesson_id'";
                $result_update_video = mysqli_query($conn, $sql_update_video);
            }else{
                echo "<p> File format is not allowed. </p>";
            }
        }
        

    }

    //Update flashcards

    //Update other details in database
    $sql_update_lesson = "UPDATE lesson_db SET lesson_name = '$lesson_name', lesson_quiz  = '$lesson_quiz' WHERE lesson_id = '$lesson_id'";
    $result_update_lesson = mysqli_query($conn, $sql_update_lesson);
    if($result_update_lesson){
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
        header("Location: manage-posts.php");
        exit();
    }else{
        echo "<p>Error updating user details. Please try again.</p>";
    }

}


?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="icon" href="data:image/jpeg;base64,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">
    <!--Gg font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Edit lesson</title>
</head>
<body>
    <nav>
        <div class="container nav__container">
            <a href="dashboard.php" class="nav__logo">EK</a>
            <ul class="nav__items">
                <li class="nav__profile">
                    <div class="avatar">
                        <img src="../user/images/animals.jpg" alt="">
                    </div>
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="signin.php">Log Out</a></li>
                    </ul>
                </li>
            </ul>

            <button id="open__nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>

    <section class="form__section">
        <div class="container form__section-container">
            <h2>Edit Lesson</h2>
            <form id="lessonForm" method="POST" action="" enctype="multipart/form-data">
            <label for="lessonOverview"><h5>Lesson Name:</h5></label>
            <textarea id="lessonOverview" name="lesson_name"><?php echo $row['lesson_name']; ?></textarea>

            <label for="imageUpload"><h5>Lesson Image:</h5></label>
            <img src="./uploadsImageLesson/<?=$row['lesson_image_url']?>" id="imagePreview">
            <input type="file" id="imageUpload" name="lesson_image_url" >

            <label for="videoLink"><h5>Lesson Video:</h5></label>
            

            <?php
            $sql = "SELECT * FROM lesson_db WHERE lesson_id = '$lesson_id'";
            $res = mysqli_query($conn, $sql);

            if(mysqli_num_rows($res) > 0){
                $video = mysqli_fetch_assoc($res)  
                    ?>
                        <video src="uploads/<?=$video['lesson_video_url']?>" controls></video>
                    <?php
                
            }else{
                echo "<h1><Empty/h1>";
            }
        ?>
            <input type="file" id="videoLink" name="lesson_video_url" >

            <label for="quizzizquest"><h5>Link Quiz:</h5></label>
            <input type="text" id="quizzizquest" name="lesson_quiz" value="<?php echo $row['lesson_quiz']; ?>">


            <label for="flashcardContainer"><h5>Lesson Flashcard:</h5></label>
            <div id="flashcardContainer">

                <div class="flashcard" name="flashcards">
                    <div class="front">
                    <h3>Front of Flashcard</h3>
                    <input type="file" class="frontTextarea" name="lesson_front_flashcard[]" value="">
                    </div>
                    <div class="back">
                    <h3>Back of Flashcard</h3>
                    <textarea class="backTextarea" name="lesson_back_flashcard[]"></textarea>
                    </div>
                </div>
            </div>
            <button type="button" id="addFlashcardBtn" class="btn">Add Flashcard</button>

            <button type="submit" class="btn btn-primary" name="submit">Update</button>
            </form>
        </div>
    </section>


    

    <!--End nav-->

    <script src="main-admin.js"></script>
    <script src="edit-lesson.js"></script>
</body>
</html>