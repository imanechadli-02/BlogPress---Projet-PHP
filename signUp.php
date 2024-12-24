<?php
include("config.php");

if (isset($_POST['submit'])) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $verify_query = mysqli_query($conn, "SELECT email FROM auteur WHERE email='$email'");


    if (mysqli_num_rows($verify_query) != 0) {
        echo "<div class='message'>
                    <p>This email is already used. Please try another one!</p>
                  </div><br>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
        $insert_query = "INSERT INTO auteur(user_fname, user_lname, email, password)
                             VALUES ('$fname', '$lname', '$email', '$hashed_password')";

        if (!mysqli_query($conn, $insert_query)) {
            echo "Error: " . mysqli_error($conn) . "<br>";
            echo "Query: " . $insert_query;
        } else {
            echo "<div class='message'>
            <p>Registration successful!</p>
          </div><br>";
            echo "<a href='signIn.php'><button class='btn'>Login Now</button></a>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">

            <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">first name</label>
                    <input type="text" name="firstname" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">last name</label>
                    <input type="text" name="lastname" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="age">email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register">
                </div>

                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>

        </div>
    </div>
</body>

</html>