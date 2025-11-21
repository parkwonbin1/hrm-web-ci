<?php
include __DIR__ . "/../config/db.php";
include __DIR__ . "/../config/minio.php";

$id = $_GET['view'] ?? null;

if (!$id) exit("잘못된 접근");

$res = $conn->query("SELECT * FROM employees WHERE emp_id='$id'");
$emp = $res->fetch_assoc();
?>

<!-- 오버레이 -->
<div class="modal-overlay" onclick="closeEmployeeModal()"></div>

<!-- 상세 모달 -->
<div class="modal-container" id="viewModal">

    <div class="modal-header">
        <h3><?= htmlspecialchars($emp['name'] ?? '') ?> 상세정보</h3>
        <button class="modal-close" onclick="closeEmployeeModal()">✕</button>
    </div>

    <div class="modal-body">
        <div style="text-align:center;">
            <img src="<?= $emp['profile_image_url'] ?: 'https://via.placeholder.com/120' ?>"
                 style="width:130px;height:130px;border-radius:10px;object-fit:cover;border:2px solid #ddd;">
        </div>

        <p><b>부서:</b> <?= htmlspecialchars($emp['department'] ?? '') ?></p>
        <p><b>직무:</b> <?= htmlspecialchars($emp['job_title'] ?? '') ?></p>
        <p><b>직책:</b> <?= htmlspecialchars($emp['position'] ?? '') ?></p>
        <p><b>입사일:</b> <?= htmlspecialchars($emp['hire_date'] ?? '') ?></p>
        <p><b>권한:</b> <?= htmlspecialchars($emp['role'] ?? '') ?></p>
        <p><b>기술스택:</b><br><?= nl2br(htmlspecialchars($emp['tech_stack'] ?? '')) ?></p>
    </div>

    <div class="modal-footer">
        <button class="btn-edit" onclick="openEditModal()">수정</button>
        <button class="btn-delete" onclick="deleteEmployee(<?= $emp['emp_id'] ?>)">삭제</button>
        <button class="btn-cancel" onclick="closeEmployeeModal()">닫기</button>
    </div>
</div>

<!-- 수정 모달 -->
<div class="modal-container hidden" id="editModal">

    <div class="modal-header">
        <h3>직원 정보 수정</h3>
        <button class="modal-close" onclick="closeEditModal()">✕</button>
    </div>

    <form id="updateForm" enctype="multipart/form-data">
        <input type="hidden" name="emp_id" value="<?= $emp['emp_id'] ?>">

        <label>이름</label>
        <input class="form-control" name="name" value="<?= $emp['name'] ?>">

        <label>부서</label>
        <input class="form-control" name="department" value="<?= $emp['department'] ?>">

        <label>직무</label>
        <input class="form-control" name="job_title" value="<?= $emp['job_title'] ?>">

        <label>직책</label>
        <input class="form-control" name="position" value="<?= $emp['position'] ?>">

        <label>입사일</label>
        <input type="date" class="form-control" name="hire_date" value="<?= $emp['hire_date'] ?>">

        <label>권한</label>
        <select class="form-control" name="role">
            <option <?= $emp['role']=='USER'?'selected':'' ?> value="USER">USER</option>
            <option <?= $emp['role']=='ADMIN'?'selected':'' ?> value="ADMIN">ADMIN</option>
        </select>

        <label>기술스택</label>
        <textarea class="form-control" name="tech_stack"><?= htmlspecialchars($emp['tech_stack'] ?? '') ?></textarea>

        <label>프로필 사진 변경</label>
        <input type="file" class="form-control" name="profile_img">

        <?php if ($emp['profile_image_url']): ?>
            <label><input type="checkbox" name="delete_image" value="1"> 사진 삭제</label>
        <?php endif; ?>

        <div class="modal-footer">
            <button type="button" class="btn-save" onclick="submitUpdate()">저장</button>
            <button type="button" class="btn-cancel" onclick="closeEditModal()">취소</button>
        </div>
    </form>

</div>

<script>
function openEditModal(){
    document.getElementById("viewModal").classList.add("hidden");
    document.getElementById("editModal").classList.remove("hidden");
}

function closeEditModal(){
    document.getElementById("editModal").classList.add("hidden");
    document.getElementById("viewModal").classList.remove("hidden");
}

function submitUpdate(){
    let fd = new FormData(document.getElementById("updateForm"));
    let emp_id = fd.get("emp_id");

    fetch("pages/employee_update_process.php", { method:"POST", body: fd })
    .then(r => r.text())
    .then(d => {
        alert("저장 완료!");

        // 기존 모달 제거
        document.getElementById("viewModal")?.remove();
        document.getElementById("editModal")?.remove();
        document.querySelector(".modal-overlay")?.remove();

        // 상세 모달 다시 로드
        fetch("modal/employee_modal.php?view=" + emp_id)
        .then(r => r.text())
        .then(html => {
            document.body.insertAdjacentHTML("beforeend", html);
            document.body.style.overflow = "hidden"; // 스크롤 다시 잠금
        });
    });
}


function deleteEmployee(id){
    if (!confirm("정말 삭제하시겠습니까?")) return;

    fetch("pages/employee_delete.php?id=" + id)
    .then(r => r.text())
    .then(d => {
        alert("삭제 완료!");

        // 모달 닫기 + 리스트 새로고침
        document.getElementById("viewModal")?.remove();
        document.querySelector(".modal-overlay")?.remove();
        document.body.style.overflow = "auto";

        // 직원 리스트 페이지 다시 로드
        location.href = "index.php?page=employees_list";
    });
}

</script>

<script>
// 모달 열릴 때 호출될 함수 (없으면 자동 실행)
document.body.style.overflow = "hidden";

function closeEmployeeModal() {
    window.top.location.reload();
}

</script>

<style>
.hidden { display:none !important; }

.modal-overlay {
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:998;
}

.modal-container {
    position:fixed;
    top:50%; left:50%;
    transform:translate(-50%,-50%);
    background:#fff;
    padding:25px;
    width:520px;
    border-radius:12px;
    z-index:999;
    max-height:90vh;
    overflow-y:auto;
}

.modal-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #eee;
    padding-bottom:10px;
    margin-bottom:15px;
}

.modal-close {
    background:none;
    border:none;
    font-size:22px;
    cursor:pointer;
}

.form-control {
    width:100%; padding:10px;
    margin-bottom:10px;
    border:1px solid #ddd;
    border-radius:6px;
}

.modal-footer {
    display:flex; justify-content:flex-end; gap:10px;
}

.btn-edit { background:#2563eb; color:#fff; padding:8px 18px; border:none; border-radius:6px; }
.btn-delete { background:#dc3545; color:#fff; padding:8px 18px; border:none; border-radius:6px; }
.btn-cancel { background:#6b7280; color:#fff; padding:8px 18px; border:none; border-radius:6px; }
.btn-save { background:#0284c7; color:#fff; padding:8px 18px; border:none; border-radius:6px; }
</style>

