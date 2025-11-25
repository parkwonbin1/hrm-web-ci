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
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM 로그인</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time() ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <span class="login-logo">🏢</span>
                <h1 class="login-title">HRM System</h1>
                <p class="login-subtitle">인사관리 시스템에 오신 것을 환영합니다</p>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">이메일</label>
                    <input class="form-control" type="email" name="email" placeholder="name@company.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">비밀번호</label>
                    <input class="form-control" type="password" name="password" placeholder="비밀번호를 입력하세요" required>
                </div>

                <button class="btn btn-primary" style="width: 100%; padding: 0.75rem; margin-top: 1rem;">로그인</button>

                <?php if($error): ?>
                    <div style="margin-top: 1.5rem; padding: 1rem; background: var(--danger-50); color: var(--danger-600); border-radius: var(--radius); font-size: 0.875rem; text-align: center;">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
