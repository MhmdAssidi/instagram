 //sending the form of comment by enter:
 document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".comment-input").forEach(input => {
        input.addEventListener("keydown", function (event) { //add event listener to detect when the Enter key is pressed.
            if (event.key === "Enter") {  //if the event of the keydown is enter then submit the form
                
                this.closest("form").submit(); 
             }
          });
        });
        });


        function displayTaskInfo(postId) { //i send the id of the post in order to have the section that we need to act on it since each section has the id of the post as id of it. 
            let taskInfo = document.getElementById("taskInfo-" + postId);
            
            if (taskInfo) {
               
                taskInfo.style.display = "flex";  
            }
        }
        
        function exitTaskInfo(postId) {
            let taskInfo = document.getElementById("taskInfo-" + postId);
            if (taskInfo) {
                taskInfo.style.display = "none";
                // document.getElementById("bigContainer").style.opacity = "1";
            }
        }


       function displayEditBtns(postId){
        console.log("Post ID:", postId);
        console.log("editPost-" + postId);
        let taskInfo = document.getElementById("editPost-" + postId);
        console.log("Post ID:", postId);

            console.log(taskInfo);
            if (taskInfo) {
                
                taskInfo.style.display = "block";  
            }
            else{
        console.log("Post ID:", postId);

            }
       }

       function exitEditInfopostId(postId){
        let taskInfo = document.getElementById("editPost-" + postId);
            
            if (taskInfo) {
                
                taskInfo.style.display = "none";  
            }
       }