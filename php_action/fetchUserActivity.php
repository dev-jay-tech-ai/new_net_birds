<?php
require_once 'core.php';

if(isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $sql = "SELECT
        idx AS post_id,
        code AS board_code,
        subject AS post_subject,
        location AS post_location,
        users.user_id,
        name AS user_name,
        content AS post_content,
        hit AS post_views,
        rate AS post_rate,
        combined.active AS post_active,
        combined.rdate AS post_date,
        username AS user_username,
        email AS user_email,
        credit AS user_credit,
        status AS user_status
    FROM
        (
            SELECT
                idx,
                code,
                subject,
                location,
                user_id,
                name,
                content,
                hit,
                rate,
                active,
                rdate
            FROM rboard
            UNION
            SELECT
                idx,
                code,
                subject,
                location,
                user_id,
                name,
                content,
                hit,
            	NULL AS rate,
                active,
                rdate
            FROM pboard
            UNION
            SELECT
                idx,
                code,
                subject,
                NULL AS location,
                user_id,
                name,
                content,
                hit,
            	NULL AS rate,
                active,
                rdate
            FROM aboard
        ) AS combined
    JOIN
        users ON combined.user_id = users.user_id
    WHERE
        users.user_id = ?
    ORDER BY
        combined.rdate DESC";
    $stmt = $connect->prepare($sql);
    if($stmt) {
      $stmt->bind_param("s", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $rs = [];
      while ($row = $result->fetch_assoc()) {
        $rs[] = $row;
      } 
    } else {
        die($connect->error);
    }
} else {
    echo '未找到用户';
}

$stmt->close();
$connect->close();
?>