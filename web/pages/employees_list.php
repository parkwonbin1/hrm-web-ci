<?php
include "./config/db.php";
include "./auth/role_admin.php";

// =========================
// 페이지네이션 설정
// =========================
$limit = 10;
$page  = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// =========================
// 검색 파라미터 처리
// =========================
$field   = $_GET['field']   ?? 'all';
$keyword = $_GET['keyword'] ?? '';

// =========================
// 검색 WHERE 조건 생성
// =========================
$where = "";

if ($keyword !== "") {
    $safeKeyword = $conn->real_escape_string($keyword);

    if ($field === "all") {
        $where = "WHERE 
            name LIKE '%$safeKeyword%' OR
            department LIKE '%$safeKeyword%' OR
            job_title LIKE '%$safeKeyword%' OR
            position LIKE '%$safeKeyword%' OR
            email LIKE '%$safeKeyword%'";
    } else {
        $safeField = $conn->real_escape_string($field);
        $where = "WHERE $safeField LIKE '%$safeKeyword%'";
    }
}

// 총 데아터 수
$countRes = $conn->query("SELECT COUNT(*) AS total FROM employees $where");
$totalRows = $countRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// 현재 페이지 데이터 
$sql = "SELECT * FROM employees $where ORDER BY emp_id DESC LIMIT $start, $limit";
$res = $conn->query($sql);
?>

<!-- 검색 박스 -->
<div class="search-box">
    <select id="search-field">
        <option value="all" <?= ($field=='all'?'selected':'') ?>>전체</option>
        <option value="name" <?= ($field=='name'?'selected':'') ?>>이름</option>
        <option value="department" <?= ($field=='department'?'selected':'') ?>>부서</option>
        <option value="job_title" <?= ($field=='job_title'?'selected':'') ?>>직무</option>
        <option value="position" <?= ($field=='position'?'selected':'') ?>>직책</option>
        <option value="email" <?= ($field=='email'?'selected':'') ?>>이메일</option>
    </select>

    <input type="text" id="search-input" 
           value="<?= htmlspecialchars($keyword) ?>" 
           placeholder="검색어 입력">

    <button onclick="searchEmployees()" class="search-btn">검색</button>
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
    <?php if($page > 1): ?>
        <a href="?page=employees_list&p=1" class="page-btn">≪</a>
    <?php endif; ?>

    <?php if($page > 1): ?>
        <a href="?page=employees_list&p=<?= $page - 1 ?>" class="page-btn">＜</a>
    <?php endif; ?>

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

    <?php if($endPage < $totalPages): ?>
        <span class="page-dots">...</span>
        <a href="?page=employees_list&p=<?= $totalPages ?>" class="page-btn">
            <?= $totalPages ?>
        </a>
    <?php endif; ?>

    <?php if($page < $totalPages): ?>
        <a href="?page=employees_list&p=<?= $page + 1 ?>" class="page-btn">＞</a>
    <?php endif; ?>

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

function searchEmployees() {
    const field = document.getElementById('search-field').value;
    const keyword = document.getElementById('search-input').value;

    location.href = `?page=employees_list&field=${field}&keyword=${encodeURIComponent(keyword)}&p=1`;
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
    gap: 14px;
    margin: 30px 0;
    font-size: 16px;
}

.page-btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-width: 28px;
    height: 28px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.page-btn.active {
    width: 38px;
    height: 38px;
    background: #eef2ff;
    color: #fff;
    border-radius: 50%;
    text-align: center;
    font-weight: 600;
    font-size: 16px;
}

.page-dots {
    color: #999;
    font-size: 16px;
}

.page-btn.arrow {
    font-size: 20px;
    font-weight: bold;
    padding: 0 10px;
}

.search-box {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-bottom: 20px;
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

.search-box .search-btn {
    padding: 10px 18px;
    background: #eef2ff;
    color: white;
    border: none;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    font-size: 16px;
}

.search-box .search-btn:hover {
    background: #3b82f6;
}

</style>

