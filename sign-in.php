<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign In</title>
    <link rel='icon' href='img/DB.png' type='image/x-icon'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php 
    include 'config.php';
    session_start();

    // Check if the user is already signed in
    if (isset($_SESSION['users'])) {
        header("Location: index.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Perform basic validation

        // Check if the user exists
        $query = "SELECT * FROM Users WHERE Username = ? AND Password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Set session variable to indicate user is signed in
            $_SESSION['users'] = $username;
            
            // Redirect to the home page or dashboard
            header("Location: index.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <div id="signin-form">
        <h2>Sign In</h2>
        <form action="sign-in.php" method="post">
            <!-- Sign-in input fields -->
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <!-- Submit button -->
            <button type="submit">Sign In</button>
        </form>
    </div>

    <script src="scripts.js"></script>
</body>
</html>