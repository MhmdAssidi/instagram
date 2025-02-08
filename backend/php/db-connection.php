<?php

$server = "localhost";  
$username = "root";     
$password = "";
$db = "instagram";    

try {
    
    $pdo = new PDO("mysql:host=$server;port=3307;dbname=$db", $username, $password);  //since port is changed not to default 3306 so we need to specify here
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
   
} catch (PDOException $exception) {    
    echo "Connection to database failed! The error is: " . $exception->getMessage();
    exit();  
}