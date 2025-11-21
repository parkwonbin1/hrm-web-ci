<?php
include "./config/db.php";
include "./auth/role_admin.php";

// =========================
// 1) 페이지네이션 설정
// =========================
$limit = 10;
$page  = isset($_GET['p']) ? (int)$_GET['p'] : 1;
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

<!-- 검색 영역 -->
<div class="search-box">
    <select id="search-field">
        <option value="all">전체</option>
        <option value="name">이름</option>
        <option value="department">부서</option>
        <option value="job_title">직무</option>
        <option value="position">직책</option>
        <option value="email">이메일</option>
    </select>

    <input type="text" id="search-input" placeholder="검색어 입력">

    <button onclick="searchEmployees()" class="search-btn">
        🔍
    </button>
</div>

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
    <!-- 맨 처음으로 -->
    <?php if($page > 1): ?>
        <a href="?page=employees_list&p=1" class="page-btn">≪</a>
    <?php endif; ?>

    <!-- 이전 페이지 -->
    <?php if($page > 1): ?>
        <a href="?page=employees_list&p=<?= $page - 1 ?>" class="page-btn">＜</a>
    <?php endif; ?>

    <!-- 페이지 번호들 (최대 5개만 표시) -->
    <?php 
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);
    
    for($i = $startPage; $i <= $endPage; $i++): 
    ?>
        <a href="?page=employees_list&p=<?= $i ?>" 
           class="page-btn <?= ($page == $i ? 'active' : '') ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <!-- ... 표시 (마지막 페이지가 멀 때) -->
    <?php if($endPage < $totalPages): ?>
        <span class="page-dots">...</span>
        <a href="?page=employees_list&p=<?= $totalPages ?>" class="page-btn">
            <?= $totalPages ?>
        </a>
    <?php endif; ?>

    <!-- 다음 페이지 -->
    <?php if($page < $totalPages): ?>
        <a href="?page=employees_list&p=<?= $page + 1 ?>" class="page-btn">＞</a>
    <?php endif; ?>

    <!-- 맨 끝으로 -->
    <?php if($page < $totalPages): ?>
        <a href="?page=employees_list&p=<?= $totalPages ?>" class="page-btn">≫</a>
    <?php endif; ?>
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
.table tr:hover { 
    background:#f3f4f6; 
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    margin: 30px 0;
    font-size: 16px;
}

.page-btn {
    display: inline-block;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    padding: 0 4px;
}

.page-btn.active {
    width: 32px;
    height: 36px;
    background: #60a5fa; 
    color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 600;

.page-dots {
    color: #999;
}

.page-btn.arrow {
    font-size: 20px;
    font-weight: bold;
    padding: 0 8px;
}

.search-box {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-bottom: 20px;
    gap: 0;
}

.search-box select {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-right: none;
    background: #f3f4f6;
    border-radius: 6px 0 0 6px;
    outline: none;
    font-size: 14px;
}

.search-box input {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-right: none;
    width: 220px;
    outline: none;
    font-size: 14px;
}

/* 버튼 (이미지처럼 오른쪽 컬러 박스) */
.search-box .search-btn {
    padding: 10px 18px;
    background: #f25c3d; 
    color: white;
    border: none;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    font-size: 16px;
}

.search-box .search-btn:hover {
    background: #d94e31;
}

</style>

