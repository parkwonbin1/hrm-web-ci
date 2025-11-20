<?php
// 세션 시작 및 데이터베이스 연결
session_start();
include __DIR__ . "/../config/db.php";
include __DIR__ . "/../config/minio.php"; // MinIO 연결 파일

// POST 요청이 아닌 경우, 잘못된 접근 처리
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('잘못된 접근');
}

// 폼 데이터 처리
$emp_id = $_POST['emp_id'];
$name = $_POST['name'];
$department = $_POST['department'];
$job_title = $_POST['job_title'];
$position = $_POST['position'];
$hire_date = $_POST['hire_date'];
$role = $_POST['role'];
$tech_stack = $_POST['tech_stack'];

// 프로필 이미지 파일 처리
$profile_img_url = $_POST['profile_img'] ?? null;
$delete_image = $_POST['delete_image'] ?? null;
$profile_image_url = null;

// 프로필 이미지 삭제
if ($delete_image == 1) {
    $res = $conn->query("SELECT profile_image_url FROM employees WHERE emp_id='$emp_id'");
    $emp = $res->fetch_assoc();
    if ($emp['profile_image_url']) {
        $key = basename($emp['profile_image_url']);
        $s3->deleteObject([
            'Bucket' => $MINIO_BUCKET,
            'Key' => $key,
        ]);
        $profile_image_url = null;
    }
}

// 프로필 이미지 업로드
if (!empty($_FILES['profile_img']['name'])) {
    $file = $_FILES['profile_img'];
    $file_name = $emp_id . '-' . basename($file['name']);
    $file_tmp = $file['tmp_name'];
    $file_path = "/tmp/$file_name";

    // MinIO에 이미지 업로드
    move_uploaded_file($file_tmp, $file_path);
    $s3->putObject([
        'Bucket' => $MINIO_BUCKET,
        'Key' => $file_name,
        'SourceFile' => $file_path,
        'ACL' => 'public-read'
    ]);

    $profile_image_url = $MINIO_PUBLIC . '/' . $file_name;
    unlink($file_path); // 임시 파일 삭제
}

// 데이터베이스 업데이트
$sql = "
    UPDATE employees 
    SET
        name = '$name',
        department = '$department',
        job_title = '$job_title',
        position = '$position',
        hire_date = '$hire_date',
        role = '$role',
        tech_stack = '$tech_stack',
        profile_image_url = '$profile_image_url'
    WHERE emp_id = '$emp_id'
";

if ($conn->query($sql)) {
    echo "업데이트 성공!";
    exit();
} else {
    echo "업데이트 실패: " . $conn->error;
    exit();
}
?>
