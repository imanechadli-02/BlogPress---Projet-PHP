<?php
include("config.php");
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM auteur WHERE email='$email'");

    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['user_fname'] = $row['user_fname'];
        $_SESSION['user_lname'] = $row['user_lname'];
        $_SESSION['id'] = $row['user_id'];

        header("Location: adminDashbord.php");
        exit;
    } else {
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