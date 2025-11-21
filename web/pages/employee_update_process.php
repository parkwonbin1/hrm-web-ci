<?php
include "../config/db.php";
include "../config/minio.php";

$emp_id     = $_POST['emp_id'];
$name       = $_POST['name'];
$department = $_POST['department'];
$job        = $_POST['job_title'];
$position   = $_POST['position'];
$hire       = $_POST['hire_date'];
$role       = $_POST['role'];
$tech       = $_POST['tech_stack'];

// 기존 정보 가져오기
$res = $conn->query("SELECT * FROM employees WHERE emp_id='$emp_id'");
$old = $res->fetch_assoc();

$old_url = $old['profile_image_url'];
$profile_url = $old_url;

// 👉 1) 기존 이미지 삭제 체크 시 MinIO에서도 삭제
if (!empty($_POST['delete_image']) && $old_url) {
    $key = basename(parse_url($old_url, PHP_URL_PATH)); // MinIO 파일명만 추출

    try {
        $s3->deleteObject([
            'Bucket' => $MINIO_BUCKET,
            'Key'    => $key
        ]);
    } catch (Exception $e) {
        // 삭제 오류 무시
    }

    $profile_url = null;
}

// 👉 2) 새 이미지 업로드
if (!empty($_FILES['profile_img']['name'])) {

    $tmp = $_FILES['profile_img']['tmp_name'];
    $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
    if (!$ext) $ext = "jpg";

    $filename = "emp_{$emp_id}_" . time() . "." . $ext;

    // PUT to MinIO
    $s3->putObject([
        'Bucket' => $MINIO_BUCKET,
        'Key'    => $filename,
        'Body'   => fopen($tmp, 'r'),
        'ACL'    => 'public-read',
        'ContentType' => mime_content_type($tmp)
    ]);

    // 저장되는 URL
    $profile_url = "{$MINIO_PUBLIC}/{$MINIO_BUCKET}/{$filename}";
}

// 👉 3) 직원 정보 업데이트
$sql = "
UPDATE employees SET
    name='$name',
    department='$department',
    job_title='$job',
    position='$position',
    hire_date='$hire',
    role='$role',
    tech_stack='$tech',
    profile_image_url=" . ($profile_url ? "'$profile_url'" : "NULL") . "
WHERE emp_id='$emp_id'
";

$conn->query($sql);

echo "OK";
?>
