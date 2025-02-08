<?php
require '../../backend/php/db-connection.php';
session_start();
if($_SERVER['REQUEST_METHOD']!="POST"){
  die("you need to submit the form");
}

$userId=$_SESSION['userId'];

if(!isset($_POST['caption']) || !isset($_FILES['post_image'])){
  die("you need to put an image or caption");

}

if(!isset($_SESSION['userId'])){
    die("you need to login");
}

$fileType = strtolower(pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION));


//Check file extention
if($fileType != "jpeg" && $fileType != "png" && $fileType != "jpg" && $fileType != "gif"){ //thats why we put tolower for the if stat here
    header("Location: /instagram/frontend/pages/profile.php?err=1");
    exit();
}
$image_name = "IMG_" . $userId . "_" . bin2hex(random_bytes(10)) . "." . $fileType; //we change the name of the image to not have same names of the image and we add random hex decimal for security reasons to not let hacker have any image.
$target = __DIR__ . "/../../frontend/postImages/" . $image_name;

//check file size
if($_FILES['post_image']['size'] > 500000){  //500,000 bytes, which is 500 KB 
    header("Location: /instagram/frontend/pages/profile.php?err=2");
    exit();
}

//Check if real image
if(!getimagesize($_FILES['post_image']['tmp_name'])){  //if the method can not return width and height for the image.
    header("Location: /instagram/frontend/pages/profile.php?err=3");
    exit();
}

    if(!move_uploaded_file($_FILES['post_image']['tmp_name'], $target)){  //if not move_uploaded_file to target
        header("Location: /instagram/frontend/pages/profile.php?err=4");
        exit();
    }

$postId=$_SESSION['postId'];

$caption=$_POST['caption'];
$postImg = $image_name; 

try{
$sql="UPDATE posts 
        SET image_post = :postImg, 
            caption=:caption
        WHERE post_id = :postId AND user_id=:userId";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":postImg", $postImg);
$stmt->bindParam(":caption", $caption);
$stmt->bindParam(":postId", $postId);

$stmt->bindParam(":userId", $userId);

$stmt->execute();
header("Location: /instagram/frontend/pages/profile.php");
}catch(Exception $ex){
    header("Location: /instagram/frontend/pages/profile.php");

    exit();
}
