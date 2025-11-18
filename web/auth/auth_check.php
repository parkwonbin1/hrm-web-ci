<?php
if (!isset($_SESSION['emp_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

