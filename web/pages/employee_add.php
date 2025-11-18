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

<h3>직원 추가</h3>
<form method="POST">
    <label>이름</label>
    <input class="form-control" name="name" required>

    <label>이메일</label>
    <input class="form-control" name="email" required>

    <label>비밀번호</label>
    <input class="form-control" name="password" required>

    <label>부서</label>
    <input class="form-control" name="department">

    <label>직무</label>
    <input class="form-control" name="job_title">

    <label>직책</label>
    <input class="form-control" name="position">

    <label>입사일</label>
    <input type="date" class="form-control" name="hire_date">

    <label>권한</label>
    <select class="form-control" name="role">
        <option value="USER">USER</option>
        <option value="ADMIN">ADMIN</option>
    </select>

    <label>기술스택</label>
    <textarea class="form-control" name="tech_stack"></textarea>

    <button class="btn btn-primary mt-3">등록</button>
</form>

