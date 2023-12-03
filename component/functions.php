<?php

function executeQuery($connect, $sql) {
    $stmt = $connect->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    } else {
        die($connect->error);
    }
}
?>