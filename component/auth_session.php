<?php
if (!isset($_SESSION['userId'])) {
    echo "<script>window.location.href='/dashboard.php';</script>";
}
?>