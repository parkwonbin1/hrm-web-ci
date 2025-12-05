<?php
date_default_timezone_set('Asia/Seoul');
$hour = date("H");
$name = $_SESSION['name'];
if ($hour >= 6 && $hour < 12) $greet = "좋은 아침이에요";
elseif ($hour >= 12 && $hour < 18) $greet = "즐거운 오후입니다";
elseif ($hour >= 18 && $hour < 23) $greet = "기분 좋은 저녁이에요";
else $greet = "늦은 밤이네요";
?>
<div class="content" style="display:flex; justify-content:center; align-items:center; min-height:calc(100vh - 140px);">
  <div class="" style="max-width:800px; width:100%; padding:2rem; text-align:center;">
    <h1 style="font-size:3rem; margin-bottom:1rem; background:linear-gradient(135deg, var(--primary-600), var(--primary-500)); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
      <?= $greet ?>
    </h1>
    <h2 style="font-size:2.5rem; color:var(--slate-700);">
      <span style="color:var(--slate-900); font-weight:800;">
        <?= $name ?>
      </span>님!
    </h2>
    <p style="margin-top:1.5rem; color:var(--slate-500); font-size:1.125rem;">
      Test  🚀
    </p>
  </div>
</div>
