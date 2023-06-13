<?php
include '../db_conn.php';
if(isset($_GET['delete'])){
    $lessonId = $_GET['delete'];

    // Fetch the back_flashcard_text from flashcard_db table
    $flashcardSql = "SELECT back_flashcard_text FROM `flashcard_db` WHERE lesson_id='$lessonId'";
    $flashcardResult = mysqli_query($conn, $flashcardSql);

    // Iterate over the fetched back_flashcard_text values and delete matching rows from lesson_learned
    while ($row = mysqli_fetch_assoc($flashcardResult)) {
        $backFlashcardText = $row['back_flashcard_text'];
        
        // Delete matching rows from lesson_learned
        $deleteLessonLearnedSql = "DELETE FROM `lesson_learned` WHERE back_flashcard_text='$backFlashcardText'";
        mysqli_query($conn, $deleteLessonLearnedSql);
    }

    // Delete rows from lesson_db
    $deleteLessonSql = "DELETE FROM `lesson_db` WHERE lesson_id='$lessonId'";
    mysqli_query($conn, $deleteLessonSql);

    // Delete rows from flashcard_db
    $deleteFlashcardSql = "DELETE FROM `flashcard_db` WHERE lesson_id='$lessonId'";
    mysqli_query($conn, $deleteFlashcardSql);

    // Reset auto-increment values
    mysqli_query($conn, "ALTER TABLE `lesson_db` AUTO_INCREMENT = 1");
    mysqli_query($conn, "ALTER TABLE `flashcard_db` AUTO_INCREMENT = 1");

    header('location: manage-posts.php');
    exit();
}
?>
