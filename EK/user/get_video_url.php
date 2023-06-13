<?php
include "../db_conn.php";
session_start();
echo $_GET['learn'];
if (isset($_GET['learn'])) {
  $lesson_id = $_GET['learn'];
  echo $lesson_id;
  

  // Query the database for the lesson details
  $query = "SELECT * FROM lesson_db WHERE lesson_id = $lesson_id";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  // Output the video source and lesson name
  echo "<video id='lesson-video' controls autoplay>
            <source src='../admin/uploads/{$row['lesson_video_url']}' type='video/mp4'>
        </video>";
  echo "<h3>{$row['lesson_name']}</h3>";

  echo" <div class='right-sidebar' id='lesson_list'>
                    <a href='lesson_play.php?learn={$row_display['lesson_id']}'>
                    
                    <div class='side-video-list'>
                        <img src='../admin/uploadsImageLesson/{$row_display['lesson_image_url']}' alt=''>
                        <div class='vid-info'>
                        <strong>{$row_display['lesson_name']}</strong>      
                        </div>
                    </div>
                    </a>  
                    ";
} else {
  echo "Error: Lesson ID not found.";
}


?>