<?php
session_start();

$url = "../../admin/view/login.php"; // mặc định

if (isset($_SESSION['user']) && $_SESSION['user']['permission'] == 3) {
    $url = "login.php";
}

session_unset();
session_destroy();
header("Location: " . $url);
exit();
?>
