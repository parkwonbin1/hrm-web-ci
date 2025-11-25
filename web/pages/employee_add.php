<?php
include "./auth/role_admin.php";
include "./config/db.php";

if ($_SERVER['REQUEST_METHOD']=='POST') {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pw    = hash('sha256', $_POST['password']);
    $dept  = $_POST['department'];
    $job   = $_POST['job_title'];
    $pos   = $_POST['position'];
    $hire  = $_POST['hire_date'];
    $role  = $_POST['role'];
    $tech  = $_POST['tech_stack'];

    $sql = "
        INSERT INTO employees
        (name,email,password,department,job_title,position,hire_date,role,tech_stack)
        VALUES
        ('$name','$email','$pw','$dept','$job','$pos','$hire','$role','$tech')
    ";

    if ($conn->query($sql)) {
        echo "<script>alert('등록 완료');location.href='index.php?page=employees_list';</script>";
    }
}
?>

<div class="content">
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="margin-bottom: 0.5rem;">직원 추가</h1>
                <p class="text-muted">새로운 직원을 시스템에 등록합니다</p>
            </div>
            <a href="index.php?page=employees_list" class="btn btn-secondary">
                목록으로 돌아가기
            </a>
        </div>

        <div class="card">
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">이름 <span style="color: var(--danger-500)">*</span></label>
                        <input class="form-control" name="name" required placeholder="홍길동">
                    </div>

                    <div class="form-group">
                        <label class="form-label">이메일 <span style="color: var(--danger-500)">*</span></label>
                        <input class="form-control" name="email" type="email" required placeholder="name@company.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">비밀번호 <span style="color: var(--danger-500)">*</span></label>
                    <input class="form-control" name="password" type="password" required placeholder="초기 비밀번호를 입력하세요">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">부서</label>
                        <input class="form-control" name="department" placeholder="예: 개발팀">
                    </div>

                    <div class="form-group">
                        <label class="form-label">직무</label>
                        <input class="form-control" name="job_title" placeholder="예: 백엔드 개발자">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">직책</label>
                        <input class="form-control" name="position" placeholder="예: 대리">
                    </div>

                    <div class="form-group">
                        <label class="form-label">입사일</label>
                        <input type="date" class="form-control" name="hire_date">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">권한</label>
                    <select class="form-control" name="role">
                        <option value="USER">일반 사용자 (USER)</option>
                        <option value="ADMIN">관리자 (ADMIN)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">기술스택</label>
                    <textarea class="form-control" name="tech_stack" rows="4" placeholder="사용 가능한 기술을 쉼표로 구분하여 입력하세요"></textarea>
                </div>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-100); display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="index.php?page=employees_list" class="btn btn-secondary">취소</a>
                    <button class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">직원 등록</button>
                </div>
            </form>
        </div>
    </div>
</div>
