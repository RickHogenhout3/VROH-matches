<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tournamentID = $_POST['tournamentID'];
    $userID = $_POST['userID'];

    // Perform basic validation

    // Insert user details into the Participants table
    $insertQuery = "INSERT INTO Participants (UserID, TournamentID) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userID, $tournamentID);

    if ($stmt->execute()) {
        echo "User added to the tournament successfully!";
    } else {
        echo "Error adding user to the tournament: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>