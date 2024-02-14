<?php
  error_reporting(E_ALL); 
  ini_set('display_errors', '1'); 

  // user table 과 조인하여 ID를 찾아야함
  $idx = 1;
  $sql = "SELECT * FROM comments WHERE idx=?";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param('i', $idx); // 'i' represents the data type of the parameter (integer)
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc(); 
?>

<div class='d-flex'>
  <div class="d-flex gap-2 w-100 my-5">
    <div class='comment-container d-flex'>
      <div class='comment'>
        <div class='main d-flex mb-4'>
          <div class='profile'><img src='./assets/images/profile/20231204045642_656d4dfada8ac.jpeg' /></div>
            <div>
              <div>$user_id</div>
              <div><?= $row['content'] ?></div>
            </div>
          </div>
          <div class='sub d-flex ms-5'>
            <div class='profile'><img src='./assets/images/profile/20231205105547_656ef3a3c25f0.jpeg'/></div>
            <div>
              <div>user name</div>
              <div>comment</div>
            </div>
          </div>
      
      </div>  
    </div>



  </div>
</div>
<style>

  .comment .profile > img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
  }
</style>