<?php
session_start();
include("config.php");

if (isset($_POST['submit'])) {
    // Securely get email and password from the POST request
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query the auteur table
    $result = mysqli_query($conn, "SELECT * FROM auteur WHERE email='$email'") or die("Query Error");

    // Fetch the user data
    $row = mysqli_fetch_assoc($result);

    // Check if user exists and verify password
    if ($row && password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['valid'] = $row['email'];
        $_SESSION['user_fname'] = $row['user_fname'];
        $_SESSION['user_lname'] = $row['user_lname'];
        $_SESSION['id'] = $row['user_id'];

        // Redirect to home page
        header("Location: adminDashbord.php");
        exit;
    } else {
        // If login fails, show an error message
        echo "<div class='message'>
                <p>Wrong Email or Password</p>
              </div> <br>";
        echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Don't have an account? <a href="signUp.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
    <script>

    </script>
</body>

</html>