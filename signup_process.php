<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform basic validation

    // Check if the username is already taken
    $checkQuery = "SELECT * FROM Users WHERE Username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Username already taken. Please choose a different username.";
        header("Refresh: 3; URL=sign-up.php");
    } else {
        // Insert the new user
        $insertQuery = "INSERT INTO Users (Username, Password) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ss", $username, $password);

        if ($insertStmt->execute()) {
            echo "Sign-up successful! Redirecting to sign-in page...";
            header("Refresh: 3; URL=sign-in.php"); // Redirect to signin.php after 3 seconds
            exit(); // Ensure no further code execution after the redirect
        } else {
            echo "Error signing up: " . $insertStmt->error;
            // Redirect to the sign-up page if there's an error
            header("Refresh: 3; URL=sign-up.php");
            exit(); // Ensure no further code execution after the redirect
        }

        $insertStmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>
