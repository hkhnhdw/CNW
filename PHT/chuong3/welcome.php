<?php
// Khởi động session (BẮT BUỘC ở mọi trang cần dùng SESSION)
session_start();

// Kiểm tra xem SESSION (lưu tên đăng nhập) có tồn tại không?
// (Giữ cùng tên khoá với trang login; ở đây dùng 'username')
if (isset($_SESSION['username'])) {

    // Nếu tồn tại, lấy username từ SESSION ra
    $loggedInUser = $_SESSION['username'];

    // In ra lời chào mừng (escape để tránh XSS)
    $safeName = htmlspecialchars($loggedInUser, ENT_QUOTES, 'UTF-8');
    echo "<h1>Chào mừng trở lại, $safeName!</h1>";
    echo "<p>Bạn đã đăng nhập thành công.</p>";

    // (Tạm thời) Tạo 1 link để "Đăng xuất" (chỉ là quay về login.html)
    echo '<a href="login.html">Đăng xuất (Tạm thời)</a>';
} else {
    // Nếu không tồn tại SESSION (chưa đăng nhập) -> chuyển hướng về login.html
    header('Location: login.html');
    exit;
}
?>