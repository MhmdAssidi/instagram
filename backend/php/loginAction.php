<?php
require('./db-connection.php');
session_start();

if($_SERVER['REQUEST_METHOD']!="POST"){
die("wrong method");
}

$contact=htmlspecialchars(trim($_POST['contact']));
$password=htmlspecialchars(trim($_POST['password']));

if($contact=="" || $password==""){
    header("Location: /instagram/frontend/pages/login.php?err=1");
    exit;
}
try{
$sql="SELECT * FROM users WHERE user_contact=:contact";
    $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":contact",$contact);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if($user && password_verify($password,$user['password'])){
            $_SESSION["userId"]=$user['id'];
            $_SESSION["username"]=$user['username'];
            $_SESSION["loggedIn"]=true;
        header("Location: /instagram/frontend/pages/index.php");

        exit();
        }

        else{
    
            header("Location: /instagram/frontend/pages/login.php?err=2");
        }

    }catch(PDOException $e){
            $_SESSION['error']="database error: ".$e->getMessage();
            header("Location: /instagram/frontend/pages/login.php");
        }