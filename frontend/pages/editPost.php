<?php
require '../../backend/php/db-connection.php';
session_start();
if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!==true){
  die("you are not logged in");
  exit();
}
$userId=$_SESSION['userId'];
if(!isset($_GET['post_id'])){
    header("Location: /instagram/frontend/pages/profile.php");
    exit();
}

else{
    $post_id=$_GET['post_id'];
    $_SESSION['postId'] = $post_id;

}
$sql="SELECT p.post_id, p.image_post, p.caption FROM posts p WHERE post_id=:post_id AND user_id=:id";
$stmt=$pdo->prepare($sql);
$stmt->bindParam(":post_id",$post_id);
$stmt->bindParam(":id",$userId);
$stmt->execute();
$post = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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
        <h2 class="text-center mb-4">Edit Post</h2>

        <div class="edit-profile-card">
           

            <!-- Edit Profile Form -->
            <form class="mt-4" method="POST" action="/instagram/backend/php/editPostAction.php" enctype="multipart/form-data">
            <div class="text-center">
            <img src="../postImages/<?php echo $post['image_post']; ?>" alt="post Picture" class="post-img">
            <input type="file" id="profile-upload" name="post_image" required> 
            </div>
            
                <div class="mb-3">
                    <label class="form-label">Caption</label>
                    <input type="text" class="form-control" name="caption" placeholder="Enter a caption" value="<?php echo $post['caption']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Edit Post</button>
                
            </form>
        </div>
    </div>

</body>
</html>
