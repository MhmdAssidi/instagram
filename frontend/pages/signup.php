<?php
session_start();
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']==true){ //if loggedIn so usually $_SESSION['loggedIn'] is isset
  die("you are logged in");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Sign Up</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>
    <div class="container">
        <div class="signup-box">
            <h1>Instagram</h1>
            <p class="signup-text">Sign up to see photos and videos from your friends.</p>
            <button class="fb-signup">Log in with Facebook</button>
            <div class="or-section">
                <div class="line"></div>
                <span>OR</span>
                <div class="line"></div>
            </div>

            <form method="POST" action="/instagram/backend/php/signupAction.php">
                <input type="text" placeholder="Mobile Number or Email Address" required name="contact">
                <input type="password" placeholder="Password" required name="password">
                <input type="text" placeholder="Full Name" required name="fullName">
                <input type="text" placeholder="Username" required name="username">
                <p class="terms-text">
                    By signing up, you agree to our <a href="#">Terms</a>, <a href="#">Privacy Policy</a>, and <a href="#">Cookies Policy</a>.
                </p>
                <button type="submit">Sign up</button>

                <?php
                if(isset($_GET['err']) && $_GET['err'] == 1) {
                        echo "<p>Please fill in all required fields: contact, password, full name, and username.</p>";
                }
                if(isset($_GET['err']) && $_GET['err'] == 2) {
                        echo "<p>Please check your phone number is written correctly.</p>";
                               }
                 if(isset($_GET['err']) && $_GET['err'] == 3) {
                            echo "<p>Password length should be greater than 8 characters.</p>";
                        
                        }
                  if(isset($_GET['err']) && $_GET['err'] == 4) {
                            echo "<p>Passoword should contains at least one letter,one number, and one special character</p>";
                                }
                  if(isset($_GET['err']) && $_GET['err'] == 5) {
                         echo "<p>You have an account, LOGIN</p>";
                     
                        }              
                ?>

            </form>

        </div>
        <div class="login-box">
            <p>Have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>
