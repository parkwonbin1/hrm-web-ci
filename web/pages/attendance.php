<?php
include "./config/db.php";

$emp = $_SESSION['emp_id'];
$today = date("Y-m-d");

// 오늘 근태 기록 조회
$sql = "SELECT * FROM attendance WHERE emp_id='$emp' AND work_date='$today'";
$res = $conn->query($sql);
$att = $res->fetch_assoc();

$has_record   = $res->num_rows > 0;
$clocked_in   = $has_record && !empty($att['clock_in_time']);
$clocked_out  = $has_record && !empty($att['clock_out_time']);
?>

<div class="att-card">

    <!-- 상단 시간 표시 -->
    <div class="time-section">
        <div id="currentTime" class="current-time"></div>
        <div class="current-date"><?= date("Y년 m월 d일 (D)") ?></div>
    </div>

    <!-- 상태 표시 -->
    <div class="status">
        <?php 
            if (!$clocked_in) echo "출근 전 입니다.";
            else if (!$clocked_out) echo "근무 중입니다.";
            else echo "퇴근 완료되었습니다.";
        ?>
    </div>

    <!-- 출근/퇴근 버튼 -->
    <div class="btn-row">

        <!-- 출근 버튼: 출근 전만 활성화 -->
        <button 
            class="btn-start <?= $clocked_in ? 'disabled' : '' ?>" 
            onclick="checkIn()" 
            <?= $clocked_in ? 'disabled' : '' ?>>
            출근
        </button>

        <!-- 퇴근 버튼: 출근 후 ~ 퇴근 전만 활성화 -->
        <button 
            class="btn-end <?= (!$clocked_in || $clocked_out) ? 'disabled' : '' ?>" 
            onclick="checkOut()" 
            <?= (!$clocked_in || $clocked_out) ? 'disabled' : '' ?>>
            퇴근
        </button>

    </div>
</div>


<script>
// 실시간 시계
function updateClock() {
    const now = new Date();
    const time = now.toLocaleTimeString("ko-KR", { hour: "2-digit", minute: "2-digit", second:"2-digit" });
    document.getElementById("currentTime").innerText = time;
}
setInterval(updateClock, 1000);
updateClock();

// 출근 요청
function checkIn() {
    location.href = "pages/attendance_check.php?type=in";
}

// 퇴근 요청
function checkOut() {
    location.href = "pages/attendance_check.php?type=out";
}
</script>


<style>
.att-card {
    width: 380px;
    margin: 30px auto;
    padding: 25px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.12);
    text-align:center;
}

/* 시간 텍스트 */
.current-time {
    font-size: 36px;
    font-weight: bold;
    color:#111;
}
.current-date {
    margin-top: 5px;
    color:#555;
    font-size:14px;
}

/* 상태 텍스트 */
.status {
    margin: 20px 0 25px;
    font-size: 18px;
    color:#333;
}

/* 버튼 영역 */
.btn-row {
    display:flex;
    justify-content: space-between;
    gap:18px;
}

/* 출근 버튼 – 연하늘색 */
.btn-start {
    flex: 1;
    background: #60a5fa;
    color:white;
    border:0;
    padding: 18px;
    font-size:18px;
    border-radius:12px;
    cursor:pointer;
    transition:0.2s;
}

/* 퇴근 버튼 – 연살구색 */
.btn-end {
    flex: 1;
    background: #fbbf24;
    color:white;
    border:0;
    padding: 18px;
    font-size:18px;
    border-radius:12px;
    cursor:pointer;
    transition:0.2s;
}

/* 비활성 상태 */
button.disabled,
button:disabled {
    background: #d1d5db !important;
    color:#888 !important;
    cursor:not-allowed !important;
}
</style>

