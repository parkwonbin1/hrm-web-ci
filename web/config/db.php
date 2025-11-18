<?php
$host = "172.16.6.141";
$user = "root";
$pass = "soldesk5.";
$db   = "hrm_db";

// SSL 완전 비활성화 
$conn = mysqli_init();
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

// SSL 플래그 제거 
mysqli_real_connect(
    $conn,
    $host, $user, $pass, $db,
    3306,
    null,
    0
);

if (mysqli_connect_errno()) {
    die("DB 연결 실패: " . mysqli_connect_error());
}

