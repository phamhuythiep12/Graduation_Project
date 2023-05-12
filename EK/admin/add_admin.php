<?php 
include "../db_conn.php";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Check if the username already exists
    $query = "SELECT * FROM `admin_db` WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // Username already exists, handle the error here (e.g. display an error message)
        echo "<script>alert('Username already exists')</script>";
        return;
    }

    // Insert the new record
    $query = "INSERT INTO `admin_db` (username, password) VALUES ('$username', '$password')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        // Redirect to the login page
        header('location: signin_admin.php');
        exit();
    } else {
        // Handle the error here (e.g. display an error message)
        die(mysqli_error($conn));
    }
}
?>