<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tournament Management</title>
    <link rel='icon' href='img/DB.png' type='image/x-icon'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'config.php'; 
    session_start();
    if (!isset($_SESSION['users'])) {
        header("Location: sign-in.php");
    }
    ?>

    <div id="tournament-list">
        <h2>Existing Tournaments</h2>
        <?php
        $result = $conn->query("SELECT TournamentID, TournamentName FROM Tournaments");
        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['TournamentName']} (<a href='view_tournament.php?tournamentID={$row['TournamentID']}'>View</a>)</li>";
            }
            echo "</ul>";
        } else {
            echo "No tournaments found.";
        }
        ?>
    </div>

    <div id="create-tournament-form">
        <h2>Create Tournament</h2>
        <form action="create_tournament.php" method="post">
            <!-- Tournament details input fields -->
            <label for="tournamentName">Tournament Name</label>
            <input type="text" name="tournamentName" id="tournamentName" required>
            <label for="format">Format</label>
            <select name="format" id="format" required>
                <option value="Single Elimination">Single Elimination</option>
                <option value="Double Elimination">Double Elimination</option>
                <option value="Round Robin">Round Robin</option>
            </select>
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" id="startDate" required>
            <label for="endDate">End Date</label>
            <input type="date" name="endDate" id="endDate" required>
            <!-- Submit button -->
            <button type="submit">Create Tournament</button>
        </form>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
