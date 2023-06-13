<?php
include '../db_conn.php';

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Fetch the user_avatar filename from user_db table
    $sql = mysqli_query($conn, "SELECT user_avatar FROM `user_db` WHERE user_id='$id'");
    $row = mysqli_fetch_assoc($sql);
    $user_avatar = $row['user_avatar'];

    // Delete user_avatar image from folder
    if (!empty($user_avatar)) {
        unlink('uploadsUserAvatar/' . $user_avatar);
    }

    // Delete user_db record
    $sql = mysqli_query($conn, "DELETE FROM `user_db` WHERE user_id='$id'");

    // Delete related records from lesson_learned table
    $sql = mysqli_query($conn, "DELETE FROM `lesson_learned` WHERE user_id='$id'");

    if($sql) {
        mysqli_query($conn, "ALTER TABLE `user_db` AUTO_INCREMENT 1");
        echo "Records deleted successfully";
        header('location:manage-users.php');
        exit();
    } else {
        die(mysqli_error($conn));
    }
}
?>
