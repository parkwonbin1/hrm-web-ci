<?php
// 시간을 한국 시간대(KST)로 설정
date_default_timezone_set('Asia/Seoul');

$hour = date("H");
$name = $_SESSION['name'];

if ($hour >= 6 && $hour < 12) $greet = "좋은 아침이에요";
else if ($hour >= 12 && $hour < 18) $greet = "즐거운 오후입니다";
else if ($hour >= 18 && $hour < 23) $greet = "기분 좋은 저녁이에요";
else $greet = "늦은 밤이네요";
?>

<div class="home-center">
    <h1 class="welcome-text">
        <?= $greet ?>, 
        <span class="welcome-name"><?= $name ?></span><span class="welcome-text">님!</span>
    </h1>
</div>

<style>
.home-center {
    width: 100%;
    height: calc(100vh - 90px); 
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.welcome-text {
    font-size: 42px;
    font-weight: 700;
    color: #2563eb;
    margin-bottom: 40px;
}

.welcome-name {
    color: #f4b400; 
    font-weight: 700;
}
</style>

