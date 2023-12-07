<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tournament Details</title>
    <link rel='icon' href='img/DB.png' type='image/x-icon'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    include 'config.php';
    session_start();

    // Check if the user is signed in
    if (!isset($_SESSION['users'])) {
        header("Location: sign-in.php");
        exit();
    }

    // Check if the tournament ID is provided in the URL
    if (!isset($_GET['tournamentID'])) {
        echo "Invalid request. Please select a tournament.";
        exit();
    }

    $tournamentID = $_GET['tournamentID'];

    // Retrieve tournament details
    $tournamentQuery = "SELECT * FROM Tournaments WHERE TournamentID = ?";
    $tournamentStmt = $conn->prepare($tournamentQuery);
    $tournamentStmt->bind_param("i", $tournamentID);
    $tournamentStmt->execute();
    $tournamentResult = $tournamentStmt->get_result();

    if ($tournamentResult->num_rows > 0) {
        $tournament = $tournamentResult->fetch_assoc();
    } else {
        echo "Tournament not found.";
        exit();
    }

    // Retrieve participants for the tournament
    $participantsQuery = "SELECT Users.Username, Participants.ParticipantID FROM Participants
                         INNER JOIN Users ON Participants.UserID = Users.UserID
                         WHERE TournamentID = ?";
    $participantsStmt = $conn->prepare($participantsQuery);

    if (!$participantsStmt) {
        die("Error preparing participants query: " . $conn->error);
    }

    $bindResult = $participantsStmt->bind_param("i", $tournamentID);

    if (!$bindResult) {
        die("Error binding parameters for participants query: " . $participantsStmt->error);
    }

    $executeResult = $participantsStmt->execute();

    if (!$executeResult) {
        die("Error executing participants query: " . $participantsStmt->error);
    }

    $participantsResult = $participantsStmt->get_result();

    // Retrieve matches for the tournament
    $matchesQuery = "SELECT Matches.MatchID, Matches.Round, 
                     P1.Username AS Player1, P2.Username AS Player2, W.Username AS Winner
                     FROM Matches
                     INNER JOIN Participants AS P1 ON Matches.Player1ID = P1.ParticipantID
                     INNER JOIN Participants AS P2 ON Matches.Player2ID = P2.ParticipantID
                     LEFT JOIN Participants AS W ON Matches.WinnerID = W.ParticipantID
                     WHERE TournamentID = ?";
    $matchesStmt = $conn->prepare($matchesQuery);

    if (!$matchesStmt) {
        die("Error preparing matches query: " . $conn->error);
    }

    $bindResultMatches = $matchesStmt->bind_param("i", $tournamentID);

    if (!$bindResultMatches) {
        die("Error binding parameters for matches query: " . $matchesStmt->error);
    }

    $executeResultMatches = $matchesStmt->execute();

    if (!$executeResultMatches) {
        die("Error executing matches query: " . $matchesStmt->error);
    }

    $matchesResult = $matchesStmt->get_result();
    ?>

    <div id="tournament-details">
        <h2>Tournament Details</h2>
        <h3><?php echo $tournament['TournamentName']; ?></h3>
        <p><strong>Format:</strong> <?php echo $tournament['Format']; ?></p>
        <p><strong>Start Date:</strong> <?php echo $tournament['StartDate']; ?></p>
        <p><strong>End Date:</strong> <?php echo $tournament['EndDate']; ?></p>
    </div>

    <div id="participants-list">
        <h2>Participants</h2>
        <?php
        if ($participantsResult->num_rows > 0) {
            echo "<ul>";
            while ($participant = $participantsResult->fetch_assoc()) {
                echo "<li>{$participant['Username']}</li>";
            }
            echo "</ul>";
        } else {
            echo "No participants found.";
        }
        ?>
    </div>

    <div id="matches-list">
        <h2>Matches</h2>
        <?php
        if ($matchesResult->num_rows > 0) {
            echo "<ul>";
            while ($match = $matchesResult->fetch_assoc()) {
                echo "<li>Round {$match['Round']}: {$match['Player1']} vs {$match['Player2']} - Winner: {$match['Winner']}</li>";
            }
            echo "</ul>";
        } else {
            echo "No matches found.";
        }
        ?>
    </div>

    <script src="scripts.js"></script>
</body>
</html>