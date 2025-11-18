<?php

// index.php 내부에서 include되면 HTML 출력이 시작됨 → header 오류 발생
// 따라서 이 파일은 직접 URL로 호출되도록 처리해야 한다.
// (즉 index.php에서 include하면 안됨)
// attendance 페이지에서 JS로 직접 호출 → 정상 작동.

// DB, 세션 로드
include __DIR__ . "/../config/db.php";
session_start();
include __DIR__ . "/../auth/auth_check.php";

// 타입: in / out
$type = $_GET['type'] ?? null;

$emp_id = $_SESSION['emp_id'];
$today  = date("Y-m-d");

// 오늘 기록 조회
$sql = "SELECT * FROM attendance WHERE emp_id='$emp_id' AND work_date='$today'";
$res = $conn->query($sql);
$att = $res->fetch_assoc();

// 출근
if ($type === "in") {

    if (!$att) {
        $conn->query("
            INSERT INTO attendance(emp_id, work_date, clock_in_time)
            VALUES ('$emp_id', '$today', NOW())
        ");
    }

    header("Location: ../index.php?page=attendance");
    exit();
}

// 퇴근
if ($type === "out") {

    if ($att && empty($att['clock_out_time'])) {
        $conn->query("
            UPDATE attendance 
            SET clock_out_time = NOW(),
                total_hours = TIMESTAMPDIFF(MINUTE, clock_in_time, NOW()) / 60
            WHERE emp_id='$emp_id' AND work_date='$today'
        ");
    }

    header("Location: ../index.php?page=attendance");
    exit();
}

header("Location: ../index.php?page=attendance");
exit();
?>

