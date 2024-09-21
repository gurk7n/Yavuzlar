<?php
require '../db.php';
require 'layout/check.php';

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT o.*, u.username, GROUP_CONCAT(CONCAT(f.name, ' (', r.name, ') ', 'x', oi.quantity) SEPARATOR ', ') as order_items 
        FROM `order` o
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN food f ON oi.food_id = f.id
        LEFT JOIN restaurant r ON f.restaurant_id = r.id
        WHERE u.username LIKE :search";

if ($filter === 'Bekleniyor') {
    $sql .= " AND o.order_status = 'Bekleniyor'";
} elseif ($filter === 'Hazırlanıyor') {
    $sql .= " AND o.order_status = 'Hazırlanıyor'";
} elseif ($filter === 'Yola Çıktı') {
    $sql .= " AND o.order_status = 'Yola Çıktı'";
} elseif ($filter === 'Teslim Edildi') {
    $sql .= " AND o.order_status = 'Teslim Edildi'";
}

$sql .= " GROUP BY o.id ORDER BY o.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Sipariş Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="my-4">Sipariş Yönetimi</h1>
                    <form class="d-flex mb-4" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Kullanıcı Adı Ara" value="<?php echo htmlspecialchars($search); ?>">
                        <select class="form-select me-2" name="filter">
                            <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Hepsi</option>
                            <option value="Bekleniyor" <?php echo $filter == 'Bekleniyor' ? 'selected' : ''; ?>>Bekleniyor</option>
                            <option value="Hazırlanıyor" <?php echo $filter == 'Hazırlanıyor' ? 'selected' : ''; ?>>Hazırlanıyor</option>
                            <option value="Yola Çıktı" <?php echo $filter == 'Yola Çıktı' ? 'selected' : ''; ?>>Yola Çıktı</option>
                            <option value="Teslim Edildi" <?php echo $filter == 'Teslim Edildi' ? 'selected' : ''; ?>>Teslim Edildi</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Ara</button>
                    </form>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kullanıcı Adı</th>
                                <th>Sipariş İçeriği</th>
                                <th>Tutar</th>
                                <th>Sipariş Durumu</th>
                                <th>Sipariş Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                                        <td><?php echo htmlspecialchars($order['order_items']); ?></td>
                                        <td><?php echo htmlspecialchars($order['total_price']); ?>₺</td>
                                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($order['created_at']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Sipariş bulunamadı.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php require 'layout/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
