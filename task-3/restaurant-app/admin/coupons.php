<?php
require '../db.php';
require 'layout/check.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT c.*, r.name as restaurant_name 
        FROM cupon c
        LEFT JOIN restaurant r ON c.restaurant_id = r.id
        WHERE c.name LIKE :search 
        ORDER BY c.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    if (isset($_POST['sil'])) {
        $stmt = $pdo->prepare("UPDATE cupon SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif (isset($_POST['aktif'])) {
        $stmt = $pdo->prepare("UPDATE cupon SET deleted_at = NULL WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kupon Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center my-4">
                        <h1 class="my-0">Kupon Yönetimi</h1>
                        <a href="coupon-add.php" class="btn btn-success">Kupon Ekle</a>
                    </div>
                <form class="d-flex mb-4" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Kupon Ara" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">Ara</button>
                </form>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Restoran</th>
                            <th>Kupon</th>
                            <th>İndirim</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Silinme Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($coupons)): ?>
                            <?php foreach ($coupons as $coupon): ?>
                                <tr class="<?php echo $coupon['deleted_at'] ? 'deleted-row' : ''; ?>">
                                    <td><?php echo htmlspecialchars($coupon['id']); ?></td>
                                    <td><?php echo htmlspecialchars($coupon['restaurant_name'] ?: 'Genel'); ?></td>
                                    <td><?php echo htmlspecialchars($coupon['name']); ?></td>
                                    <td><?php echo htmlspecialchars('%' . intval($coupon['discount'])); ?></td>
                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($coupon['created_at']))); ?></td>
                                    <td><?php echo htmlspecialchars($coupon['deleted_at'] ? date('d-m-Y', strtotime($coupon['deleted_at'])) : ''); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $coupon['id']; ?>">
                                            <?php if ($coupon['deleted_at'] === null): ?>
                                                <button type="submit" name="sil" class="btn btn-danger btn-sm">Sil</button>
                                            <?php else: ?>
                                                <button type="submit" name="aktif" class="btn btn-success btn-sm">Aktifleştir</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Kupon bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php require 'layout/footer.php' ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
