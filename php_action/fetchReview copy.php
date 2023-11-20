<?php
require_once 'core.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(isset($_POST["rating_data"])) {
    $user_name = $_POST["user_name"];
    $user_rating = $_POST["rating_data"];
    $user_review = $_POST["user_review"];
    $datetime = time();
    $query = "
        INSERT INTO review
        (user_name, user_rating, user_review, datetime) 
        VALUES (?, ?, ?, ?)
    ";
    $statement = $connect->prepare($query);
    // Bind parameters
    $statement->bind_param('sisi', $user_name, $user_rating, $user_review, $datetime);
    // Execute the statement
    $statement->execute();
    echo "Your Review & Rating Successfully Submitted";
}

if(isset($_POST["action"])) {
    $review_content = array();

    $query = "SELECT * FROM review ORDER BY review_id DESC";
    $stmt = $connect->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch_assoc()) {
        $review_content[] = array(
            'user_name'     => $row["user_name"],
            'user_review'   => $row["user_review"],
            'rating'        => $row["user_rating"],
            'datetime'      => date('l jS, F Y h:i:s A', $row["datetime"])
        );

        if ($row["user_rating"] == '5') $five_star_review++;
        if ($row["user_rating"] == '4') $four_star_review++;
        if ($row["user_rating"] == '3') $three_star_review++;
        if ($row["user_rating"] == '2') $two_star_review++;
        if ($row["user_rating"] == '1') $one_star_review++;
        $total_review++;
        $total_user_rating = $total_user_rating + $row["user_rating"];
    }

    $output = array(
        'review_data' => $review_content
    );

    echo json_encode($output);
}



?>