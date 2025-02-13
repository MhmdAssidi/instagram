<?php
require '../../backend/php/db-connection.php';

session_start();
header('Content-Type: application/json'); // set response header for JSON, when browser call this knows it will return json page

if (!isset($_SESSION["userId"])) {
    
    header("Location: ../../frontend/pages/login.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']!="POST"){
    die("wrong method");
    }

$userId=$_SESSION['userId'];
$postId=$_POST['postId'];
$action = $_POST['action'];   

try{
    if($action=="like"){

$sql="INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id);";
$stmt=$pdo->prepare($sql);
$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':post_id', $postId);
$stmt->execute();
echo json_encode(['success' => true, 'message' => 'like successfully']);
}
else{
    $sql="DELETE FROM likes WHERE user_id = :userId AND post_id = :postId";
    $stmt=$pdo->prepare($sql);
$stmt->bindParam(":userId",$userId);
$stmt->bindParam(":postId",$postId);
$stmt->execute();

echo json_encode(['success' => true, 'message' => 'unlike successfully']);
}
}catch (PDOException $exception) {    
    echo json_encode(['success' => false, 'message' => "Error: " . $exception->getMessage()]); 
 }