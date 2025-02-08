<?php
require '../../backend/php/db-connection.php';

session_start();
// Check if userId is set in session
if (!isset($_SESSION["userId"])) {
    // Redirect to login page if user is not logged in
    header("Location: ../../frontend/pages/login.php");
    exit();
}
 $userId=$_GET["userId"];  
 //I make inner join between posts and users on user id to have the userinfo and post info of the user.  
 //this sql may return one row or many rows according to user how many post he has.
 //each post returned the info of the user will be returned with it, so when we make fetchAll the result is assoc array where from first index we can have all the info.
 $sql="SELECT p.post_id, p.image_post, p.caption, p.user_id, p.date_created, 
       u.fullname, u.username, u.profile_image, u.bio 
FROM posts p 
INNER JOIN users u ON p.user_id = u.id 
WHERE u.id = :id AND p.isDeleted=0 ORDER BY p.date_created DESC;
";
    $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":id",$userId);
        $stmt->execute();
        $userAndHisPosts = $stmt->fetchAll();        
        
       
        $count = count($userAndHisPosts); // get the number of posts

if ($count > 0) {
    $userInfo = $userAndHisPosts[0]; 
} else {
    // fetch user info separately if there are no posts
    $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$userInfo){
        die("User not found.");
    }
}

        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/profile.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
 
    .post .moreoptions{
   position: relative;
}
.post .moreoptions svg{
    position: absolute;
    bottom: 5%;
    left: 87%;
    cursor: pointer;
}
    .taskInfo {
        display: none;
        justify-content: center;
    align-items: center;
    position: fixed;
    top: 35%;
    bottom: auto;
    right: 17%;
    width: 70%;
    max-width: 70%;
    height: 10%;
    z-index: 9999;

}


.taskInfo .taskInfoContainer{

box-shadow: 10px 100px 150px rgba(0, 0, 0, 0.5);
padding: 30px;
font-size: 25px;
font-weight: 600;
background-color:rgb(197, 194, 199);
position: relative;
width: 100%;
 
}
.taskInfoContainer label {
    font-size: 18px;
    font-weight: bold;
    color: #000;
    display: block;
    margin-bottom: 5px;
}

.taskInfo .taskInfoContainer i{
position: absolute;
left: 90%;
}

@media screen and (max-width:750px) {

.taskInfo{
  
    right: 5%;
    width: 90%;
    max-width: none;
}
}

.commentAndItsDate{
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-top: 12px;
}
.commentAndItsDate .commentAuthor{
    font-size: 15px;

}
.commentAndItsDate .commentContent{
color:rgb(79 72 72);
font-size: 20px;
}

.commentAndItsDate .dateOfComment{
    font-size: 12px;

}
.replyForm{
    display: flex;
    justify-content: space-around;
    position: absolute;
    width: 100%;
    top: 70%;
    left: 5%;
    background-color: rgba(117, 117, 117, 0);
    border: none;
}
.replyBtn{
    border: none;
    font-size: 12px;
    background-color: rgba(0, 0, 0, 0);
    font-weight: 500;
    position: absolute;
    top: 55%;
    right: 82%;
    left: 12%;

}
.replyInput{
    right: 47%;
    left: 23%;
}
@media screen and (max-width:950px) {

.replyInput {
    right: 18%;
    left: 33%;
}
}
@media screen and (max-width:450px) {
    .commentAndItsDate .commentAuthor {
    font-size: 10px;
}

.commentAndItsDate .commentContent {
    font-size: 13px;
}


}

 .itsAReply{
justify-content: space-around;

}

/* edit and delete buttons sections: */

.editBtnsContainer{
    position: relative;
}
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 72px;
    background: white;
    padding: 100px;
    border-radius: 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 95%;
    height: 95%;
    text-align: center;
    position: fixed;
    bottom: 0%;
        }

        .action-buttons button {
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s ease;
        }

        .btn-delete {
            background: #ed4956;
            color: white;
        }

        .btn-delete:hover {
            background: #c13545;
        }

        .btn-edit {
            background: #ffffff;
            color: black;
            border: 1px solid #dbdbdb;
        }

        .btn-edit:hover {
            background: #f5f5f5;
        }

.editPost{
    display: none;
   z-index: 9999;
}

.editPostForm button{
    width: 100%;
}


</style>
</head>
<body>

    <div class="container mt-5">
        <!-- Profile Header -->
        <div class="profile-header d-flex align-items-center">
            <img src="../images/<?php echo $userInfo['profile_image']  ?>" alt="Profile Picture" class="profile-img">
            <div class="profile-info ms-4">
                <div class="d-flex align-items-center">
                    <h2 class="username"><?php echo $userInfo['username']  ?></h2>

                    <?php
                    if($userId==$_SESSION['userId']){
                        echo '
                        <a href="./editProfile.php">
                    <button class="btn btn-outline-secondary ms-3">Edit Profile</button>
                    </a>
                        ';
                    }
                    ?>
                    
                    
                </div>
                <div class="stats mt-2">
                <span><strong><?php echo $count ?></strong> <?php echo $count == 1 ? 'post' : 'posts'; ?></span>
                    <span><strong>10.5K</strong> followers</span>
                    <span><strong>500</strong> following</span>
                </div>
                <div class="bio mt-2">
                    <p><strong><?php echo $userInfo['username']  ?></strong><br><?php echo $userInfo['bio']  ?></p>
                </div>
            </div>
        </div>

<!-- logout Button -->
 <div>
<a href="../../backend/php/logout.php" class="btn btn-danger position-absolute top-0 end-0 m-3">Logout</a>
</div>   

</div>
        <hr>

        <!-- Posts Container (Flexbox) -->
        <div class="container">
        <div class="row">
        <?php
        
foreach ($userAndHisPosts as $post) {
   //retreive the comments for each post:
    $postId=$post['post_id'];
       
    $sql2="SELECT u.username,u.id,c.comment_id,c.content,c.created_at, c.user_id, c.isAReply, 
    c.parent_comment_id ,c.post_id FROM comments c INNER JOIN posts p INNER JOIN users u ON
     c.post_id=p.post_id AND u.id=c.user_id WHERE p.post_id=:post_id";
     $stmt2=$pdo->prepare($sql2);
     $stmt2->bindParam(":post_id",$postId);
     $stmt2->execute();
     $comments = $stmt2->fetchAll();   
     $nBOfComments=count($comments);

     echo '
     <!-- Post 1 -->
     <div class="post">
         <div class="col-md-8 col-12 mb-4 mx-auto"> 
        ';   if($userId==$_SESSION['userId']){
         
         echo '   <div class="moreoptions" onclick="displayEditBtns(' . $post['post_id'] . ')">

                  
                       <svg aria-label="More Options" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor" height="24" role="img" viewBox="0 0 24 24" width="24">
                                <title>More Options</title>
                                <circle cx="12" cy="12" r="1.5"></circle>
                                <circle cx="6" cy="12" r="1.5"></circle>
                                <circle cx="18" cy="12" r="1.5"></circle>
                            </svg> 
        
           </div>' ;       
        }

         echo '  <img src="../postImages/' . $post['image_post'] . '" alt="" class="post-img">

            

            <div class="post-details">
                <div class="post-actions d-flex align-items-center">
                    <i class="fa-regular fa-heart"></i> <span>1,245 likes</span>
                     <i class="fa-regular fa-comment ms-3" onclick="displayTaskInfo(' . $post['post_id'] . ')"></i> <span>'.$nBOfComments.' comments</span><br>
               
                    </div>
                    <span class="text-muted mt-1" style="font-size: 12px;">' . $post['date_created'] . '</span>

                <p class="post-bio"><strong>' . $post['username'] . '</strong> ' . $post['caption'] . '</p>
          <!-- Comment Input Field -->
            <form class="comment-form" method="POST" action="../../backend/php/AddCommentAction.php">
                <input type="hidden" name="post_id" value='.$post['post_id'] .'>
                <input type="text" name="commentContent" class="form-control comment-input" placeholder="Add a comment...">
             
            
                </form>
          
                </div>
        </div>
        
   </div>
    ';
    
     
       
    ?>
    <!-- Comment section  -->

      <?php   echo '  <section class="taskInfo" id="taskInfo-' . $post['post_id'] . '">

                <div class="taskInfoContainer">
                <i class="fa fa-close" style="font-size:24px; cursor: pointer;" id="exit" onclick="exitTaskInfo(' . $post['post_id'] . ')"></i>
                <div class="mb-3">
                <label for="TaskName" class="form-label" style="font-size:30px">Comments</label>
                
                    ';
                            foreach ($comments as $comment) {
                                
                                if ($comment['isAReply'] == 0) { //its a comment:
                                    echo '<div class="commentAndItsDate">';
                                    echo '<p class="commentAuthor">' . htmlspecialchars($comment['username']).': <span class="commentContent">'. htmlspecialchars($comment['content']).'</span>'.'</p>';
                                    echo '<p class="dateOfComment">'.htmlspecialchars($comment['created_at']).'</p>';
                                  
                                    echo '<form method="POST" action="../../backend/php/AddCommentAction.php" class="replyForm">
                                  
                                    <input type="hidden" name="comment_id" value='.$comment['comment_id'] .'>  
                                    <input type="hidden" name="post_id" value='.$post['post_id'] .'>  

                                    <input type="text" class="replyBtn replyInput" name="commentContent" class="form-control comment-input" placeholder="Add a Reply...">
                                     <button class="_a9ze replyBtn" type="submit"><span class="x193iq5w xeuugli x1fj9vlw x13faqbe x1vvkbs xt0psk2 x1i0vuye x1fhwpqd x1s688f x1roi4f4 x10wh9bi x1wdrske x8viiok x18hxmgj">Reply</span></button>
                                 
                                    </form>';
                                  
                                    echo '</div>';
                                }
                                if ($comment['isAReply'] == 1) { //its a reply:
                                    echo '<div class="commentAndItsDate itsAReply">';
                                    echo '<p class="commentAuthor">' . htmlspecialchars($comment['username']).': <span class="commentContent">'. htmlspecialchars($comment['content']).'</span>'.'</p>';
                                    echo '<p class="dateOfComment">'.htmlspecialchars($comment['created_at']).'</p>';
                                  
                                    echo '<form method="POST" action="../../backend/php/AddCommentAction.php" class="replyForm">
                                  
                                    <input type="hidden" name="comment_id" value='.$comment['comment_id'] .'>  
                                    <input type="hidden" name="post_id" value='.$post['post_id'] .'>  

                                    <input type="text" class="replyBtn replyInput" name="commentContent" class="form-control comment-input" placeholder="Add a Reply...">
                                     <button class="_a9ze replyBtn" type="submit"><span class="x193iq5w xeuugli x1fj9vlw x13faqbe x1vvkbs xt0psk2 x1i0vuye x1fhwpqd x1s688f x1roi4f4 x10wh9bi x1wdrske x8viiok x18hxmgj">Reply</span></button>
                                 
                                    </form>';
                                  
                                    echo '</div>';
                                }


                            }
                  echo '
                            </div>
                    <hr>

                </div>

            </section>
                            
   ';


       echo '      <section class="editPost" id="editPost-' . $post['post_id'] . '">

             <div class="container mt-5 d-flex justify-content-center editBtnsContainer">

                 <div class="action-buttons">
        <button type="button" class="btn-close btn-dark" aria-label="Close" id="exit" onclick="exitEditInfopostId(' . $post['post_id'] . ')"></button>

                      <form method="POST" action="../../backend/php/deletePostAction.php" class="editPostForm"> 
                        <input type="hidden" name="post_id" value='.$post['post_id'] .'>  
                           
                     <button class="btn-delete" type="submit">
                         <i class="fas fa-trash-alt"></i> Delete
                     </button>
                      </form>      

                      
                        
                        <a href="./editPost.php?post_id='.$post['post_id'].'" style="text-decoration:none;"> 
                     <button class="btn-edit">
                         <i class="fas fa-edit"></i> Edit
                     </button>
                     </a>
                      </form>      

                 </div>
             </div>
             </section>

             ';

}
?>
</div>
</div>





<script src="../javascript/profile.js"></script>
<script src="https://kit.fontawesome.com/YOUR_UNIQUE_KIT.js" crossorigin="anonymous"></script>

</body>
</html>
