<?php
// TODO 1: Khởi động session
// Phải gọi hàm này TRƯỚC BẤT KỲ output HTML nào
session_start();

// TODO 2: Kiểm tra xem người dùng đã nhấn nút "Đăng nhập" (gửi form) chưa
if (isset($_POST['username'])) {

    // TODO 3: Nếu đã gửi form, lấy dữ liệu 'username' và 'password' từ $_POST
    $user = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    // TODO 4: (Giả lập) Kiểm tra logic đăng nhập
    // Nếu $user == 'admin' VÀ $pass == '123' thì là đăng nhập thành công
    if ($user === 'admin' && $pass === '123') {

        // TODO 5: Nếu thành công, lưu tên username vào SESSION
        $_SESSION['username'] = $user;

        // TODO 6: Chuyển hướng người dùng sang trang "chào mừng"
        header('Location: welcome.php');
        exit;

    } else {
        // Nếu thất bại, chuyển hướng về login.html
        header('Location: login.html?error=1');
        exit;
    }

} else {
    // TODO 7: Nếu người dùng truy cập trực tiếp file này (không qua POST),
    // "đá" họ về trang login.html
    header('Location: login.html');
    exit;
}
?>