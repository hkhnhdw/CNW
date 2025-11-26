<?php
// Hàm load dữ liệu từ JSON
function loadFlowers() {
    if (file_exists('flowers.json')) {
        $json = file_get_contents('flowers.json');
        return json_decode($json, true);
    }
    return [];
}

// Hàm save dữ liệu vào JSON
function saveFlowers($flowers) {
    file_put_contents('flowers.json', json_encode($flowers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Xử lý CRUD cho admin (qua POST)
if (isset($_GET['role']) && $_GET['role'] === 'admin') {
    $flowers = loadFlowers();

    // Add new
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $newFlower = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'image' => $_POST['image']  
        ];
        $flowers[] = $newFlower;
        saveFlowers($flowers);
    }

    // Delete
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $index = $_POST['index'];
        unset($flowers[$index]);
        $flowers = array_values($flowers);  
        saveFlowers($flowers);
    }

    // Edit
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $index = $_POST['index'];
        $flowers[$index]['name'] = $_POST['name'];
        $flowers[$index]['description'] = $_POST['description'];
        $flowers[$index]['image'] = $_POST['image'];
        saveFlowers($flowers);
    }
}

// Load flowers để hiển thị
$flowers = loadFlowers();
$isAdmin = (isset($_GET['role']) && $_GET['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hoa</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .flower { margin-bottom: 20px; }
        img { max-width: 300px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>14 loại hoa tuyệt đẹp thích hợp trồng để khoe hương sắc dịp xuân hè</h1>

<?php if (!$isAdmin): ?>
    <?php foreach ($flowers as $flower): ?>
        <div class="flower">
            <h2><?php echo $flower['name']; ?></h2>
            <img src="<?php echo $flower['image']; ?>" alt="<?php echo $flower['name']; ?>">
            <p><?php echo $flower['description']; ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <h2>Quản lý danh sách hoa</h2>
    <table>
        <tr>
            <th>Tên hoa</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($flowers as $index => $flower): ?>
            <tr>
                <td><?php echo $flower['name']; ?></td>
                <td><?php echo substr($flower['description'], 0, 100) . '...'; ?></td>
                <td><img src="<?php echo $flower['image']; ?>" alt="" style="width:50px;"></td>
                <td>
                    <!-- Form edit -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="text" name="name" value="<?php echo $flower['name']; ?>" required>
                        <textarea name="description" required><?php echo $flower['description']; ?></textarea>
                        <input type="text" name="image" value="<?php echo $flower['image']; ?>" required>
                        <button type="submit">Sửa</button>
                    </form>
                    <!-- Form delete -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Form add new -->
    <h3>Thêm hoa mới</h3>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <label>Tên hoa: <input type="text" name="name" required></label><br>
        <label>Mô tả: <textarea name="description" required></textarea></label><br>
        <label>Path ảnh: <input type="text" name="image" required placeholder="images/ten_hoa.jpg"></label><br>
        <button type="submit">Thêm</button>
    </form>
<?php endif; ?>

</body>
</html>