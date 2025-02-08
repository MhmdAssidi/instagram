<?php
require '../../backend/php/db-connection.php';
session_start();
if($_SERVER['REQUEST_METHOD']!="POST"){
  die("you need to submit the form");
}

$userId=$_SESSION['userId'];
//to add the image to the database:
//1- check the type of the file if it is png or etc..
//2-we need to change the name of the image to not have duplicate images when return it, so we add random hex decimal plus the type of the image in a variable.


$fileType = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));


//Check file extention
if($fileType != "jpeg" && $fileType != "png" && $fileType != "jpg" && $fileType != "gif"){ //thats why we put tolower for the if stat here
    header("Location: /instagram/frontend/pages/editProfile.php?err=1");
    exit();
}
$image_name = "IMG_" . $userId . "_" . bin2hex(random_bytes(10)) . "." . $fileType; //we change the name of the image to not have same names of the image and we add random hex decimal for security reasons to not let hacker have any image.
$target = __DIR__ . "/../../frontend/images/" . $image_name;

//check file size
if($_FILES['profile']['size'] > 500000){  //500,000 bytes, which is 500 KB 
    header("Location: /instagram/frontend/pages/editProfile.php?err=2");
    exit();
}

//Check if real image
if(!getimagesize($_FILES['profile']['tmp_name'])){  //if the method can not return width and height for the image.
    header("Location: /instagram/frontend/pages/editProfile.php");
    exit();
}

    if(!move_uploaded_file($_FILES['profile']['tmp_name'], $target)){  //if not move_uploaded_file to target
        header("Location: /instagram/frontend/pages/editProfile.php?err=4");
        exit();
    }
    
$fullname=$_POST['fullname'];
$username=$_POST['username'];
$bio=$_POST['bio'];
$contact=$_POST['contact'];

    try{

        $sql="UPDATE users 
        SET user_contact = :contact, 
            fullname = :fullname, 
            username = :username, 
            profile_image = :imageName, 
            bio = :bio 
        WHERE id = :id
        ";
            $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":contact", $contact);
    $stmt->bindParam(":fullname", $fullname);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":imageName", $image_name);
    $stmt->bindParam(":bio", $bio);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();
    header("Location: /instagram/frontend/pages/editProfile.php");
    $_SESSION['updated']="Updated Successfully";

    exit();
} catch(Exception $ex){
    header("Location: /instagram/frontend/pages/editProfile.php?err=3");

    exit();
}
