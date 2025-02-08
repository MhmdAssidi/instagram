<?php
require('./db-connection.php');
session_start();

if($_SERVER['REQUEST_METHOD']!="POST"){
die("wrong method");
}

$post_id=$_POST['post_id'];
$user_id=$_SESSION['userId'];
$sql="UPDATE posts SET isDeleted=1 WHERE post_id=:post_id AND user_id=:user_id";
$stmt = $pdo->prepare($sql);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    header("Location: /instagram/frontend/pages/profile.php");