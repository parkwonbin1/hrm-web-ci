<?php
include "./config/db.php";
include "./auth/role_admin.php";

// =========================
// 1) 페이지네이션 설정
// =========================
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// 총 직원 수
$countRes = $conn->query("SELECT COUNT(*) as total FROM employees");
$totalRows = $countRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// 현재 페이지 데이터 
$sql = "SELECT * FROM employees ORDER BY emp_id DESC LIMIT $start, $limit";
$res = $conn->query($sql);
?>


<div id="employee-list">
 <!-- 직원 목록 -->
<table class="table">
    <thead>
        <tr>
            <th>이름</th>
            <th>부서</th>
            <th>직무</th>
            <th>직책</th>
            <th>입사일</th>
            <th>이메일</th>
        </tr>
    </thead>

    <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
            <tr onclick="openEmployee(<?= $row['emp_id'] ?>)" style="cursor:pointer;">
                <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['department'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['job_title'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['position'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['hire_date'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- 페이지네이션 버튼 -->
<div class="pagination">
    <?php if($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="page-btn">＜ 이전</a>
    <?php endif; ?>

    <?php for($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=employees_list&p=<?= $i ?>" 
           class="page-btn <?= ($page == $i ? 'active' : '') ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if($page < $totalPages): ?>
        <a href="?page=employees_list&p=<?= $page + 1 ?>" class="page-btn">다음 ＞</a>
    <?php endif; ?>
    </div>
</div>

<div id="modal-area"></div>
<!-- 직원 상세 모달 열기 -->
<script>
function openEmployee(id){
    fetch("modal/employee_modal.php?view=" + id)
    .then(res => res.text())
    .then(html => {
        document.getElementById("modal-area").innerHTML = html;

        //  모달 내부의 <script> 강제로 실행
        const scripts = document.querySelectorAll("#modal-area script");
        scripts.forEach(oldScript => {
            const newScript = document.createElement("script");
            newScript.text = oldScript.textContent;
            document.body.appendChild(newScript).remove();
        });
    });
}


function closeEmployeeModal() {
    document.getElementById("modal-area").innerHTML = "";
    document.body.style.overflow = "auto";
    location.reload();
}
</script>

<style>
.table {
    width:100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding:10px;
    border-bottom:1px solid #ddd;
}
.table tr:hover { background:#f3f4f6; }
</style>

