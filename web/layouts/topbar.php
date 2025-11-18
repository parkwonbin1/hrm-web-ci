<?php
$role = $_SESSION['role'];
?>
<div class="topnav">
    <div class="left">
        <span class="logo">HRM 시스템</span>

        <a href="index.php?page=home" class="menu <?= $page=='home'?'active':'' ?>">홈</a>

        <?php if($role=='ADMIN'): ?>
            <a href="index.php?page=employees_list" class="menu <?= $page=='employees_list'?'active':'' ?>">직원 관리</a>
        <?php endif; ?>

        <a href="index.php?page=attendance" class="menu <?= $page=='attendance'?'active':'' ?>">근태 관리</a>
        <a href="index.php?page=my_profile" class="menu <?= $page=='my_profile'?'active':'' ?>">내 정보</a>
    </div>

    <div class="right">
        <span class="user-info"><?= $_SESSION['name'] ?></span>
        <a href="auth/logout.php" class="logout">로그아웃</a>
    </div>
</div>

<style>
.topnav {
    position: fixed;
    top:0; left:0; right:0;
    height:100px;
    background:white;
    border-bottom:1px solid #e5e7eb;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 25px;
    z-index:10;
}
.topnav .logo {
    font-size:20px;
    font-weight:700;
    margin-right:30px;
}
.topnav .menu {
    margin-right:20px; 
    text-decoration:none;
    color:#555;
    font-size:18px;
    padding:8px 12px;
    border-radius:6px;
    font-weight: 700;
}
.topnav .menu.active {
    background:#eef2ff;
    color:#3b82f6;
    font-weight:600;
}
.right .logout {
    margin-left:15px;
    text-decoration:none;
    color:#dc2626;
}
.content {
    margin-top:90px;
    padding:25px;
}
</style>

