<?php
include "./config/db.php";

// 세션 시작 (이미 시작됐으면 무시)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$me = $_SESSION['emp_id'] ?? null;

if (!$me) {
    exit("잘못된 접근");
}

$sql = "SELECT * FROM employees WHERE emp_id='$me'";
$r = $conn->query($sql);
$emp = $r->fetch_assoc();
?>

<div class="profile-wrapper">

    <!-- 상단 프로필 카드 -->
    <div class="profile-header">

        <div class="profile-photo">
            <img src="<?= $emp['profile_image_url'] ?: 'https://via.placeholder.com/120' ?>">
        </div>

        <div class="profile-basic">
            <h2><?= htmlspecialchars($emp['name']) ?></h2>
            <p class="role-text"><?= htmlspecialchars($emp['job_title'] ?: $emp['role']) ?></p>
        </div>

    </div>

    <!-- 인사정보 -->
    <div class="section-title">인사정보</div>
    <div class="profile-section">
        <div class="row"><span class="label">부서</span><span><?= htmlspecialchars($emp['department']) ?></span></div>
        <div class="row"><span class="label">직책</span><span><?= htmlspecialchars($emp['position']) ?></span></div>
        <div class="row"><span class="label">입사일</span><span><?= htmlspecialchars($emp['hire_date']) ?></span></div>
    </div>

    <!-- 기본정보 -->
    <div class="section-title">기본정보</div>
    <div class="profile-section">
        <div class="row"><span class="label">이메일</span><span><?= htmlspecialchars($emp['email']) ?></span></div>
        <div class="row"><span class="label">기술스택</span><span><?= nl2br(htmlspecialchars($emp['tech_stack'])) ?></span></div>
    </div>

</div>

<style>
/* 전체 레이아웃 */
.profile-wrapper {
    width: 900px;
    margin: 40px auto;
    background: #f9fafb;
    padding: 40px;
    border-radius: 14px;
}

/* 상단 프로필 */
.profile-header {
    display: flex;
    align-items: center;
    margin-bottom: 40px;
    gap: 20px;
}

.profile-photo img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.profile-basic h2 {
    font-size: 28px;
    margin: 0;
    font-weight: 700;
}

.role-text {
    color: #6b7280;
    margin-top: 4px;
}

/* 섹션 제목 */
.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 40px 0 15px 0;
}

/* 정보 테이블 */
.profile-section {
    background: white;
    border-radius: 10px;
    padding: 20px 30px;
    margin-bottom: 30px;
}

.row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e5e7eb;
}

.row:last-child {
    border-bottom: none;
}

.label {
    color: #6b7280;
    width: 130px;
}
</style>

