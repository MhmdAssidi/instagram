<?php

require('./db-connection.php');
session_start();

if($_SERVER['REQUEST_METHOD']!="POST"){
die("wrong method");
}

$contact=htmlspecialchars(trim($_POST['contact']));
$password=htmlspecialchars(trim($_POST['password']));
$fullName=htmlspecialchars(trim($_POST['fullName']));
$username=htmlspecialchars(trim($_POST['username']));

if($contact=="" || $password=="" || $fullName=="" || $username==""){
    header("Location: /instagram/frontend/pages/signup.php?err=1");
    exit;
}

// Check if contact is email or phone number
if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
    // Valid email
} elseif (preg_match('/^\+?[0-9]{7,15}$/', $contact)) {
    // Valid phone number (international format, e.g., +1234567890)
} else {
    // Invalid contact (neither email nor phone number)
    header("Location: /instagram/frontend/pages/signup.php?err=2");

    exit;
}

// Check if the password is strong:
    if (strlen($password) < 8) {
        header("Location: /instagram/frontend/pages/signup.php?err=3");
        exit;
    }
    
    // Check if the password contains at least one letter,one number, and one special character
    if (!preg_match("/[A-Za-z]/", $password) || 
        !preg_match("/[0-9]/", $password) || 
        !preg_match("/[\W_]/", $password)) {
        header("Location: /instagram/frontend/pages/signup.php?err=4");
        exit;
    }
   
   //hashing the password:
       $hashed_password=password_hash($password,PASSWORD_BCRYPT);

 //now check if the user is already have an account:
    
    $sql="SELECT * FROM users WHERE user_contact=:contact AND fullname=:fullname AND username=:username";
    $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":contact",$contact);
        $stmt->bindParam(":fullname",$fullName);
        $stmt->bindParam(":username",$username);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if($user){
            header("Location: /instagram/frontend/pages/signup.php?err=5");

        exit;
        }

    $sql2="INSERT INTO users (user_contact, password, fullname, username) VALUES (:contact, :password, :fullname, :username)";    
    
    $stmt=$pdo->prepare($sql2);
    $stmt->bindParam(":contact",$contact);
    $stmt->bindParam(":password",$hashed_password);
    $stmt->bindParam(":fullname",$fullName);
    $stmt->bindParam(":username",$username);
    if($stmt->execute()){
        $_SESSION['userId']=$pdo->lastInsertId();   //since we may have many users is register on the same time and we need to go to hom page without the user make login after signup so directly go to home page,we need to store the id of the last user is inserted in the database we have lastInsterId method since we have many pdo objects since many users make connection with database
        $_SESSION['loggedIn']=true;
        $_SESSION['name']=$username;
        header("Location: /instagram/frontend/pages/index.php");

        exit;
    }
    

