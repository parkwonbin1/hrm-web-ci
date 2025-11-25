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

// 총 데이터 수
$countRes = $conn->query("SELECT COUNT(*) AS total FROM employees $where");
$totalRows = $countRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// 현재 페이지 데이터 
$sql = "SELECT * FROM employees $where ORDER BY emp_id DESC LIMIT $start, $limit";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Apple SD Gothic Neo', sans-serif;
            background: #f5f7fa;
            color: #1e293b;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        /* 헤더 섹션 */
        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 15px;
            color: #64748b;
            font-weight: 400;
        }

        /* 상단 액션 바 */
        .action-bar {
            background: white;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .stats-summary {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-content h3 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }

        .stat-content p {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        /* 검색 박스 */
        .search-container {
            background: white;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .search-box {
            display: flex;
            gap: 12px;
            align-items: stretch;
        }

        .search-select-wrap {
            position: relative;
            min-width: 140px;
        }

        .search-select {
            width: 100%;
            height: 48px;
            padding: 0 40px 0 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            color: #334155;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20' fill='%23334155'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .search-select:hover {
            border-color: #cbd5e1;
        }

        .search-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-input {
            flex: 1;
            height: 48px;
            padding: 0 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        .search-btn {
            height: 48px;
            padding: 0 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .search-btn:active {
            transform: translateY(0);
        }

        /* 테이블 컨테이너 */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .table thead tr th {
            padding: 18px 24px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
            cursor: pointer;
        }

        .table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }

        .table tbody td {
            padding: 20px 24px;
            font-size: 15px;
            color: #334155;
        }

        .table tbody td:first-child {
            font-weight: 600;
            color: #0f172a;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #f0f9ff;
            color: #0369a1;
        }

        /* 페이지네이션 */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 40px 0 20px 0;
        }

        .page-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            border-radius: 10px;
            transition: all 0.2s;
            background: white;
            border: 2px solid #e2e8f0;
        }

        .page-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .page-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .page-dots {
            color: #cbd5e1;
            font-size: 18px;
            padding: 0 8px;
        }

        /* 모달 오버레이 */
        #modal-area {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }

        /* 반응형 */
        @media (max-width: 1024px) {
            .action-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-summary {
                width: 100%;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .search-box {
                flex-direction: column;
            }

            .search-select-wrap,
            .search-input,
            .search-btn {
                width: 100%;
            }

            .table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 800px;
            }

            .stats-summary {
                gap: 16px;
            }

            .stat-item {
                flex: 1;
                min-width: calc(50% - 8px);
            }
        }

        /* 로딩 애니메이션 */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table tbody tr {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 페이지 헤더 -->
        <div class="page-header">
            <h1 class="page-title">👥 직원 관리</h1>
            <p class="page-subtitle">조직의 모든 직원 정보를 한눈에 확인하고 관리하세요</p>
        </div>

        <!-- 상단 통계 바 -->
        <div class="action-bar">
            <div class="stats-summary">
                <div class="stat-item">
                    <div class="stat-icon">👤</div>
                    <div class="stat-content">
                        <h3><?= number_format($totalRows) ?></h3>
                        <p>총 직원 수</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 검색 영역 -->
        <div class="search-container">
            <div class="search-box">
                <div class="search-select-wrap">
                    <select id="search-field" class="search-select">
                        <option value="all" <?= $field === 'all' ? 'selected' : '' ?>>전체</option>
                        <option value="name" <?= $field === 'name' ? 'selected' : '' ?>>이름</option>
                        <option value="department" <?= $field === 'department' ? 'selected' : '' ?>>부서</option>
                        <option value="job_title" <?= $field === 'job_title' ? 'selected' : '' ?>>직무</option>
                        <option value="position" <?= $field === 'position' ? 'selected' : '' ?>>직책</option>
                        <option value="email" <?= $field === 'email' ? 'selected' : '' ?>>이메일</option>
                    </select>
                </div>

                <input type="text" 
                       id="search-input" 
                       class="search-input" 
                       placeholder="검색어를 입력하세요..."
                       value="<?= htmlspecialchars($keyword) ?>"
                       onkeypress="if(event.key==='Enter') searchEmployees()">

                <button onclick="searchEmployees()" class="search-btn">
                    🔍 검색
                </button>
            </div>
        </div>

        <!-- 직원 목록 테이블 -->
        <div class="table-container">
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
                    <?php if($res->num_rows > 0): ?>
                        <?php while($row = $res->fetch_assoc()): ?>
                            <tr onclick="openEmployee(<?= $row['emp_id'] ?>)">
                                <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['department'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['job_title'] ?? '') ?></td>
                                <td><span class="badge"><?= htmlspecialchars($row['position'] ?? '') ?></span></td>
                                <td><?= htmlspecialchars($row['hire_date'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px; color: #94a3b8;">
                                검색 결과가 없습니다.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 페이지네이션 -->
        <?php if($totalPages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=employees_list&field=<?= $field ?>&keyword=<?= urlencode($keyword) ?>&p=1" class="page-btn">≪</a>
                <a href="?page=employees_list&field=<?= $field ?>&keyword=<?= urlencode($keyword) ?>&p=<?= $page - 1 ?>" class="page-btn">‹</a>
            <?php endif; ?>

            <?php 
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            for($i = $startPage; $i <= $endPage; $i++): 
            ?>
                <a href="?page=employees_list&field=<?= $field ?>&keyword=<?= urlencode($keyword) ?>&p=<?= $i ?>" 
                   class="page-btn <?= ($page == $i ? 'active' : '') ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if($endPage < $totalPages): ?>
                <span class="page-dots">...</span>
                <a href="?page=employees_list&field=<?= $field ?>&keyword=<?= urlencode($keyword) ?>&p=<?= $totalPages ?>" class="page-btn">
                    <?= $totalPages ?>
                </a>
            <?php endif; ?>

            <?php if($page < $totalPages): ?>
                <a href="?page=employees_list&field=<?= $field ?>&keyword=<?= urlencode($keyword) ?>&p=<?= $page + 1 ?>" class="page-btn">›</a>
                <a href="?page=employees_list&field=<?= $field ?>&keyword=<?= urlencode($keyword) ?>&p=<?= $totalPages ?>" class="page-btn">≫</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div id="modal-area"></div>

    <script>
        function openEmployee(id) {
            fetch("modal/employee_modal.php?view=" + id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("modal-area").innerHTML = html;
                    document.body.style.overflow = "hidden";

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
</body>
</html>