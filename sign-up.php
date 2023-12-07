<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up</title>
    <link rel='icon' href='img/DB.png' type='image/x-icon'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'config.php'; ?>

    <div id="signup-form">
        <h2>Sign Up</h2>
        <form action="signup_process.php" method="post">
            <!-- Sign-up input fields -->
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <!-- Add more sign-up fields as needed -->
            <!-- Submit button -->
            <button type="submit">Sign Up</button>

            <p>Already have an account? <a href="sign-in.php">Sign in</a></p>
        </form>
    </div>

    <script src="scripts.js"></script>
</body>
</html>