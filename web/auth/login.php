<?php
session_start();
include "../config/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $pw    = $_POST['password'];

    $sql = "SELECT emp_id, name, role, password FROM employees WHERE email='$email'";
    $res = $conn->query($sql);

    if ($res->num_rows == 1) {
        $u = $res->fetch_assoc();

        if ($u['password'] === hash('sha256', $pw)) {

            $_SESSION['emp_id'] = $u['emp_id'];
            $_SESSION['name']   = $u['name'];
            $_SESSION['role']   = $u['role'];

            header("Location: ../index.php?page=home");
            exit();
        }
    }

    $error = "잘못된 로그인 정보입니다.";
}
?>
<html>
<head>
<meta charset="UTF-8">
<title>HRM 로그인</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f4f5f8; }
.box {
    width:380px; margin:120px auto;
    background:white; padding:30px;
    border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
<div class="box">
    <h3 class="text-center mb-3">HRM 로그인</h3>

    <form method="POST">
        <label>이메일</label>
        <input class="form-control mb-3" type="email" name="email" required>

        <label>비밀번호</label>
        <input class="form-control mb-3" type="password" name="password" required>

        <button class="btn btn-primary w-100">로그인</button>

        <?php if($error): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
    </form>
</div>
</body>
</html>

