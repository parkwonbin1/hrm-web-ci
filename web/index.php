<?php
// 출력 버퍼가 없으면 생성
if (!ob_get_level()) ob_start();

session_start();
include "auth/auth_check.php";

$page = $_GET['page'] ?? "home";
$view = $_GET['view'] ?? null;

$titles = [
    "home"             => "홈",
    "employees_list"   => "직원 목록",
    "employee_add"     => "직원 추가",
    "employee_edit"    => "직원 수정",
    "my_profile"       => "내 정보",
    "attendance"       => "근태 관리",
];

$page_title = $titles[$page] ?? "HRM 시스템";
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php include "layouts/topbar.php"; ?>

<div class="content">
<?php
    $file = "pages/$page.php";
    if (file_exists($file)) include $file;
    else echo "<h3></h3>";
?>
</div>

<?php
// 직원 모달
if ($page === 'employees_list' && $view) {
    include "modal/employee_modal.php";
}
?>

</body>
</html>

<?php
// 출력 버퍼가 존재할 때만 종료
if (ob_get_level() > 0) ob_end_flush();
?>

