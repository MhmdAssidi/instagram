<?php
require '../../backend/php/db-connection.php';
session_start();

header('Content-Type: application/json'); // set response header for JSON, qhwn browser call this knwos it will return json page
//we echo json_encode so always this page will return json.
//as we return json not html page by header we need to fetch this data, the code of fetching in javascript is by postman and then we change what we need in javascript
if (!isset($_SESSION['userId'])){
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}
if (!isset($_POST['userId'])){
    echo json_encode(['success' => false, 'message' => 'User ID missing']);
    exit();
}

$followerId=$_SESSION['userId'];  //user who is loggedIn,made the follow action, so its id is in follower_id column
//and the following_id is the id for who loggedIn user make follow, its from get array:
    
$followingId=$_POST['userId']; 
$action = $_POST['action'];   
try{
    if($action=="follow"){
$sql="INSERT INTO follow ( follower_id, following_id) VALUES (:followerId, :followingId);";
$stmt=$pdo->prepare($sql);
 $stmt->bindParam(":followerId",$followerId);
 $stmt->bindParam(":followingId",$followingId);
 $stmt->execute();

 echo json_encode(['success' => true, 'message' => 'Followed successfully']);
    }
    else{
        $sql="DELETE FROM follow WHERE follower_id = :followerId AND following_id = :followingId";
        $stmt=$pdo->prepare($sql);
 $stmt->bindParam(":followerId",$followerId);
 $stmt->bindParam(":followingId",$followingId);
 $stmt->execute();

 echo json_encode(['success' => true, 'message' => 'Unfollowed successfully']);
    }
}catch (PDOException $exception) {    
    echo json_encode(['success' => false, 'message' => "Error: " . $exception->getMessage()]); 
 }
