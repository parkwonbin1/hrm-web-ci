i<?php
require __DIR__ . "/../vendor/autoload.php";

use Aws\S3\S3Client;

$MINIO_ENDPOINT = "http://172.16.6.143:9000";   // API 포트 9000
$MINIO_PUBLIC   = "http://172.16.6.143:9000/hrm-profile"; // 공개 URL
$MINIO_BUCKET   = "hrm-profile";

$MINIO_KEY      = "admin";
$MINIO_SECRET   = "admin1234";

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => $MINIO_ENDPOINT,
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key'    => $MINIO_KEY,
        'secret' => $MINIO_SECRET,
    ],
]);

