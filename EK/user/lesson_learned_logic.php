<?php
include "../db_conn.php";
session_start();

$username = $_SESSION['username'];
// Prepare and execute a database query to retrieve the user's information
$query = "SELECT * FROM user_db WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if a row was found
if ($result->num_rows > 0) {
    // Fetch the row as an associative array
    $row_users = $result->fetch_assoc();

    // Retrieve the user ID from the row
    $userIdFromDB = $row_users['user_id'];
}



// Check if the request contains the flashcard data
if (isset($_POST['flashcards'])) {
    // Get the flashcard data from the request
    $flashcards = $_POST['flashcards'];

    // Perform the necessary database operations to save the flashcard data
    // Iterate over the flashcards and save each one
    foreach ($flashcards as $flashcard) {
        $frontUrl = $flashcard['frontUrl'];
        $backText = $flashcard['backText'];

        // Check if the flashcard already exists in the database
        $checkQuery = "SELECT * FROM lesson_learned WHERE user_id = ? AND front_flashcard_url = ? AND back_flashcard_text = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('iss', $userIdFromDB, $frontUrl, $backText);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Flashcard already exists, skip saving it
            continue;
        }

        // Prepare and execute a database query to insert the flashcard data into the lesson_learned table
        $insertQuery = "INSERT INTO lesson_learned (user_id, front_flashcard_url, back_flashcard_text) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('iss', $userIdFromDB, $frontUrl, $backText);
        $insertStmt->execute();
    }

    // Send a response back to the JavaScript indicating the successful save
    echo json_encode([$flashcards, $userIdFromDB]);
} else {
    // Send a response back to the JavaScript indicating the missing flashcard data
    echo json_encode(['success' => false, 'message' => 'Flashcard data not found']);
}

?>
