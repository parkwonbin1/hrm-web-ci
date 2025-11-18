<?php
include "./config/db.php";
include "./auth/role_admin.php";

$sql = "SELECT * FROM employees ORDER BY emp_id DESC";
$res = $conn->query($sql);
?>

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

<div id="modal-area"></div>

<script>
function openEmployee(id){
    fetch("modal/employee_modal.php?view=" + id)
    .then(res => res.text())
    .then(html => {
        document.getElementById("modal-area").innerHTML = html;

        // 🔥 모달 내부의 <script> 강제로 실행
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

