<?php
    include '../db_conn.php';
    if(isset($_GET['delete'])){
        $id=$_GET['delete'];
        $sql = mysqli_query($conn,"SELECT * FROM `user_db` WHERE user_id='$id'");
        $row = mysqli_fetch_assoc($sql);
        $user_avatar = $row['user_avatar'];
        
        // Delete image from folder
        if (!empty($user_avatar)) {
            unlink('uploadsUserAvatar/' . $user_avatar);
        }
        $sql= mysqli_query($conn,"DELETE from `user_db` WHERE user_id='$id'");
        
        if($sql){
            mysqli_query($conn,"ALTER TABLE `user_db` AUTO_INCREMENT 1");

            header('location:manage-users.php');
        }else{
            die(mysqli_error($conn));
        }
    }
?>