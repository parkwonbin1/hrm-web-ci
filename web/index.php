<?php
$servername = "172.16.6.141";  // DB-M
$username = "root";
$password = "soldesk5.";
$dbname = "hrm_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("DB 연결 실패: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>HRM 인사관리 시스템</title>

  <!-- ✅ Bootstrap + DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

  <style>
    body {
      background-color: #f6f7fb;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 1300px;
      margin-top: 50px;
    }
    .card {
      border: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border-radius: 12px;
    }
    .card-header {
      background-color: #343a40;
      color: #fff;
      font-size: 1.3rem;
      font-weight: bold;
      padding: 15px 20px;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    table.dataTable tbody tr:hover {
      background-color: #f2f7ff;
    }
    th, td {
      white-space: nowrap;
      vertical-align: middle;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="card">
      <div class="card-header">
        📋 HRM 인사관리 시스템 (<?php echo gethostname(); ?>)
      </div>
      <div class="card-body">
        <table id="employeeTable" class="table table-striped table-bordered align-middle" style="width:100%">
          <thead class="table-light">
            <tr>
              <th>사번</th>
              <th>이름</th>
              <th>부서</th>
              <th>직무</th>
              <th>직책</th>
              <th>입사일</th>
              <th>근속일수</th>
              <th>이메일</th>
              <th>기술스택</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT emp_id, name, department, job_role, position, hire_date, tenure_days, email, tech_stack 
                    FROM employees ORDER BY department, position";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['emp_id']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['department']}</td>
                      <td>{$row['job_role']}</td>
                      <td>{$row['position']}</td>
                      <td>{$row['hire_date']}</td>
                      <td>{$row['tenure_days']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['tech_stack']}</td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#employeeTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 20, 50],
        "scrollX": true,
        "language": {
          "search": "🔍 검색:",
          "lengthMenu": "페이지당 _MENU_ 개씩 보기",
          "info": "총 _TOTAL_명 중 _START_ ~ _END_명 표시",
          "paginate": { "previous": "이전", "next": "다음" },
          "zeroRecords": "일치하는 결과가 없습니다."
        }
      });
    });
  </script>

</body>
</html>

<?php $conn->close(); ?>
