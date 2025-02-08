<?php
require '../../backend/php/db-connection.php';

session_start();

if (!isset($_SESSION["userId"])) {
    
    header("Location: ../../frontend/pages/login.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']!="POST"){
    die("wrong method");
    }


 $userId=$_SESSION["userId"];  

 $content=htmlspecialchars($_POST['commentContent']);
 if($content===""){
   header("Location: ../../frontend/pages/profile.php");
exit();
 }
 $postId=$_POST['post_id'];
 $parentCommentId=NULL;
$isAReply=0;
 // if its a reply, set the parent_comment_id to the value from the form
 if(isset($_POST['comment_id']) && $_POST['comment_id'] != 'NULL'){
    $parentCommentId=$_POST['comment_id']; 
    $isAReply=1;
 }

 //if the parentcommentid not reach this file so the user has written a comment:
 try{
 $sql="INSERT INTO comments (content, post_id, user_id, parent_comment_id, isAReply)
VALUES (:content, :postId, :userId, :parentCommentId, :isAReply);";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':postId', $postId);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':parentCommentId', $parentCommentId);
    $stmt->bindParam(':isAReply', $isAReply);
    $stmt->execute();
    header("Location: /instagram/frontend/pages/profile.php");
 }catch (PDOException $exception) {    
    echo "error adding the comment,the error is: " . $exception->getMessage();
    exit();  
 }

