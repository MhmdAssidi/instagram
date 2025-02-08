<?php
session_start();
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']==true){
  die("you are logged in");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>Instagram</h1>
            <form method="POST" action="/instagram/backend/php/loginAction.php">
                <input type="text" placeholder="Phone number, or Email Address" name="contact" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit">Log in</button>
                <div class="or-section">
                    <div class="line"></div>
                    <span>OR</span>
                    <div class="line"></div>
                </div>
                <a href="#" class="fb-login">Log in with Facebook</a>
                <a href="#" class="forgot-password">Forgot password?</a>
                <?php
            
        if(isset($_GET['err']) && $_GET['err'] == 1) {
           
            echo "<p>Please fill in all required fields:  password and email address or phone number</p>";
               
            }
    
     if(isset($_GET['err']) && $_GET['err'] == 2) {
            echo "<p>Sorry, your password or email address or the phone number was incorrect. Please double-check them.</p>";
            }
    
          ?>
            </form>
        </div>
        <div class="signup-box">
            <p>Don't have an account? <a href="./signup.php">Sign up</a></p>
        </div>
    </div>
</body>
</html>
