<!-- add_user_to_tournament.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add User to Tournament</title>
</head>
<body>
    <?php include 'config.php'; ?>

    <div id="user-tournament-form">
        <h2>Add User to Tournament</h2>
        <form action="add_user_to_tournament_process.php" method="post">
            <!-- Select the tournament to add the user to -->
            <label for="tournamentID">Select Tournament</label>
            <select name="tournamentID" id="tournamentID" required>
                <!-- Populate this dropdown with existing tournaments from the database -->
                <?php
                $result = $conn->query("SELECT TournamentID, TournamentName FROM Tournaments");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"{$row['TournamentID']}\">{$row['TournamentName']}</option>";
                }
                ?>
            </select>

            <!-- Add user details input fields -->
            <label for="userID">User ID</label>
            <input type="text" name="userID" id="userID" required>

            <!-- Add more user details input fields as needed -->

            <!-- Submit button -->
            <button type="submit">Add User to Tournament</button>
        </form>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
