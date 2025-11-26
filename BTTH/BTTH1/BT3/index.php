<?php
// Đọc file CSV
$filename = '65HTTT_Danh_sach_diem_danh.csv';

if (!file_exists($filename)) {
    die("<h3 style='color:red;text-align:center;'>Không tìm thấy file students.csv!</h3>");
}

$students = [];
if (($handle = fopen($filename, "r")) !== FALSE) {
    $header_line = fgets($handle); 
    $header_line = trim($header_line);
    
    if (strpos($header_line, "\xEF\xBB\xBF") === 0) {
        $header_line = substr($header_line, 3); 
    }
    
    $header = str_getcsv($header_line); 
    $header = array_map('trim', $header); 

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $data = array_pad($data, count($header), '');
        $students[] = array_combine($header, $data);
    }
    fclose($handle);
}
$total = count($students);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Sinh Viên - CSE485</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-users"></i> Danh Sách Sinh Viên Môn Học</h2>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>MSSV</th>
                            <th>Họ và tên</th>
                            <th>Lớp</th>
                            <th>Email</th>
                            <th>Môn học</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td class="text-center"><strong><?= $index + 1 ?></strong></td>
                            <td><code><?= htmlspecialchars($student['username']) ?></code></td>
                            <td>
                                <strong><?= htmlspecialchars($student['lastname'] . ' ' . $student['firstname']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($student['city']) ?></td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($student['email']) ?>" class="email-link">
                                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($student['email']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-course">
                                    <?= htmlspecialchars($student['course1']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>