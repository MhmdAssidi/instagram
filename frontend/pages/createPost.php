<?php
session_start();

if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!=true){
    die("you are not logged in");
  }

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
    background-color: #000000;
    color: white; 
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
    width: 320px;
    height: 320px;
    object-fit: cover;
    display: block;
    margin: auto;

}

</style>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Add a Post</h2>

        <div class="edit-profile-card">
           

            <!--adding post Form -->
            <form class="mt-4" method="POST" action="/instagram/backend/php/createPostAction.php" enctype="multipart/form-data">
            <div class="text-center">
            <img src="" alt="" class="profile-img">
            <input type="file" id="profile-upload" name="profile"> 
            </div>
            
                <div class="mb-3">
                    <label class="form-label">Caption:</label>
                    <textarea class="form-control" rows="3" name="caption" placeholder="Write something.."></textarea>
                </div>


                <button type="submit" class="btn btn-primary w-100">Post</button>
                
        
            </form>
        </div>
    </div>

</body>
</html>
