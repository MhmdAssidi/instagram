<?php
require '../../backend/php/db-connection.php';
session_start();
if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!==true){
  die("you are not logged in");
}
$userId=$_SESSION['userId'];
$sql="SELECT * FROM users WHERE id=:id";
$stmt=$pdo->prepare($sql);
    $stmt->bindParam(":id",$userId);
    $stmt->execute();
    $user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style> 
    body {
        background-color: #ffffff;
        color: #000000;
}

.edit-profile-card {
    max-width: 500px;
    margin: auto;
    background: #121212;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
}

/* Form Inputs */
.form-control {
    background-color: #1e1e1e; 
    color: white; 
    border: 1px solid #444; 
}

.form-control::placeholder {
    color: #bbb; 
}

/* Profile Picture */
.profile-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: auto;
    border: 3px solid #555;
}

</style>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Profile</h2>

        <div class="edit-profile-card">
           

            <!-- Edit Profile Form -->
            <form class="mt-4" method="POST" action="/instagram/backend/php/editProfileAction.php" enctype="multipart/form-data">
            <div class="text-center">
            <img src="../images/<?php echo $user['profile_image']; ?>" alt="Profile Picture" class="profile-img">
            <input type="file" id="profile-upload" name="profile"> 
            </div>
            
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname" placeholder="Enter your full name" value="<?php echo $user['fullname']; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter your username" value="<?php echo $user['username']; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bio</label>
                    <textarea class="form-control" rows="3" name="bio" placeholder="Write something about yourself..."><?php echo $user['bio']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email or Phone Number</label>
                    <input type="email" class="form-control" name="contact" placeholder="Enter your email" value="<?php echo $user['user_contact']; ?>">
                </div>


                <button type="submit" class="btn btn-primary w-100">Submit</button>
                <?php
        if(isset($_GET['err']) && $_GET['err'] == 1) {
           
            echo "<p>The image should be of type png, jpg, or gif</p>";

       
                }
                if(isset($_GET['err']) && $_GET['err'] == 2) {
           
                    echo "<p>The image should be less size</p>";
                  
                           }
                if(isset($_GET['err']) && $_GET['err'] == 3) {
           
                     echo "<p>There is error try again</p>";
                        
                       }    
                       if(isset($_GET['err']) && $_GET['err'] == 4) {
           
                        echo "<p>image not save try again</p>";
                           
                          }       
                ?>
            </form>
        </div>
    </div>

</body>
</html>
