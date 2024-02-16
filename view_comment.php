<?php
if (isset($_SESSION['userId'])) {
  $user_id = $_SESSION['userId'];
  $_sql = "SELECT * FROM users WHERE user_id = {$user_id}";
  $_query = $connect->query($_sql);
  $_result = $_query->fetch_assoc();
  $initial = strtoupper(mb_substr($_result['username'], 0, 1, 'UTF-8'));
}

$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx']) ? $_GET['idx'] : '');
if ($idx == '') {
    exit('Not allow to the abnormal access');
}
$sql = "SELECT * FROM comments c INNER JOIN users u ON c.user_id = u.user_id WHERE post_id=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param('i', $idx);
$stmt->execute();
$result = $stmt->get_result();
$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
?>

<div class='mx-5'>Comments (<?= count($comments) ?>)<hr class='my-4' /></div>

<div class='view_comments d-flex'>
  <div class="d-flex gap-2 w-100 my-5 ms-5">
    <div class='comment-container d-flex'>
      <div class='comments'>
        <?php
        foreach ($comments as $comment) {
        ?>
        <div class='main d-flex mb-4'>
          <div class='comment' data-idx='<?= $comment['idx']; ?>'>
          <div class='username'>
            <div class='board_profile'>
              <img src='./assets/images/profile/20231204045642_656d4dfada8ac.jpeg' />
            </div>
            <div><?= $comment['username']; ?></div>
          </div>
          <div class='mt-3 mb-2'><?= $comment['content']; ?></div>
          <div class='bottom'>
            <button class='btn-unset reply-btn'><i class="fa fa-comments-o" aria-hidden="true"></i> Reply</button>
            &nbsp; · &nbsp;<?= $comment['create_at']; ?>&nbsp; &nbsp;
            <?php
            if(isset($_SESSION['userId'])) {
              if($_SESSION['userId'] === $comment['user_id'] || $_result['status'] == 1) {
            ?>  
                <button class='btn-unset edit'> Edit</button>
                <button class='btn-unset delete'> Delete</button>
            <?php  
              }
            }
            ?>
          </div>
          <?php
            $sqlSub = "SELECT cre.*, u.* 
            FROM comments_re cre 
            INNER JOIN comments c ON cre.parent_id = c.idx 
            INNER JOIN users u ON cre.user_id = u.user_id
            WHERE c.post_id=?";
            $stmtSub = $connect->prepare($sqlSub);
            $stmtSub->bind_param('i', $idx);
            $stmtSub->execute();
            $resultSub = $stmtSub->get_result();
            $subComments = [];
            while ($rowSub = $resultSub->fetch_assoc()) {
              $subComments[] = $rowSub;
            }
            echo "<div class='sub m-4'>";
            foreach ($subComments as $sub) {
              if($comment['idx'] === $sub['parent_id']) {
                echo "<div class='comment mb-4'>";
                echo "<div class='username'>";
                echo "<div class='board_profile'><img src='./assets/images/profile/20231205105547_656ef3a3c25f0.jpeg'/></div>";
                echo "<div>" . $sub['username'] . "</div>";
                echo "</div>";
                echo "<div class='mt-3 mb-2'>" . $sub['content'] . "</div>";
                echo "<div class='bottom'>" . $sub['create_at'] . "&nbsp; &nbsp;";
                if(isset($_SESSION['userId'])) {
                  if($_SESSION['userId'] === $sub['user_id'] || $_result['status'] == 1) {
                    echo "<button class='btn-unset edit'> Edit</button>";
                    echo "<button class='btn-unset delete'> Delete</button>";
                  }
                } 
                echo "</div></div>";
              }
            }
            echo "</div>";
          ?>
          </div>
        </div>
        <?php }
        ?>
      </div>

    </div>
  </div>
</div>

<style>
  .comment .username {
    display: flex;
    gap: 8px;
  }
  .bottom {
    font-size: 12px;
  }
  .btn-unset {
    all: unset;
    padding: 0 2.5px;
  }

</style>
<script>
  const replyBtns = document.querySelectorAll('.reply-btn');
  replyBtns.forEach((replyBtn, idx) => {
    replyBtn.addEventListener('click', () => {
      const parentEle = replyBtn.parentNode;
      const replySection = parentEle.nextElementSibling;
      if(replySection && replySection.classList.contains('reply-section')) {
        replySection.remove();
      } else {
        const html = `<div class="reply-section d-flex gap-2 m-2">
          <textarea name="" class="comment_content form-control" rows="3"></textarea>
          <button class="btn btn-secondary btn-comment">Comment</button>
        </div>`;
        parentEle.insertAdjacentHTML("afterend", html);
      }
    })
  })

  document.addEventListener("click", function(e){
    const btn = e.target.parentNode.lastElementChild; // Or any other selector.
    if(!e.target.className.includes('comment_content') && btn.className.includes('btn-comment')) {
      const comment_content = e.target.parentNode.firstElementChild;
      if(comment_content.value == '') {
        alert('댓글 내용을 입력 바랍니다!!'); // 중국어 변역 필요
        comment_content.focus();
        return false;
      }
			const parent_id = e.target.parentNode.parentNode.dataset.idx;
			const user_id = <?= json_encode($_SESSION['userId']); ?>;
      const formData = new FormData();
			formData.append('user_id', user_id);
			formData.append('parent_id', parent_id);
			formData.append('idx', param['idx']);
			formData.append('content', comment_content.value);
			const xhr = new XMLHttpRequest();
			xhr.open('post', './php_action/fetchComment.php', true);
			xhr.send(formData)
			xhr.onload = () => {
				if(xhr.status == 200) {
					try {	
						const data = JSON.parse(xhr.responseText)
						if(data.result == 'success') {
							alert('Success!')
							self.location.href = '/list.php?code='+code+'&idx='+param['idx'];
						} else alert('Failed'); // alert('Failed'+ data.message); // Display the error message
					} catch(error) {
						console.error('Error parsing JSON:', error);
					}
				} else alert('Error: ' + xhr.status);
			}

    }
    
  });


</script>