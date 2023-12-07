<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $tournamentName = $_POST['tournamentName'];
    $format = $_POST['format'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Perform basic validation (you should add more validation based on your requirements)

    // Insert tournament details into the Tournaments table
    $insertQuery = "INSERT INTO Tournaments (OrganizerID, TournamentName, Format, StartDate, EndDate) VALUES (1, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);

    // Assuming OrganizerID is hardcoded to 1 for simplicity. In reality, it should be dynamically obtained based on the logged-in user.
    
    // Bind parameters and execute the query
    $stmt->bind_param("ssss", $tournamentName, $format, $startDate, $endDate);
    
    if ($stmt->execute()) {
        echo "Tournament created successfully!";
    } else {
        echo "Error creating tournament: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
