<?php
require 'db.php';
require 'query/check.php';

$sql = "SELECT c.*, r.name as restaurant_name 
        FROM cupon c
        LEFT JOIN restaurant r ON c.restaurant_id = r.id
        WHERE c.deleted_at IS NULL
        ORDER BY c.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kuponlarım</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center my-4">
                    <h1 class="my-0">Kuponlarım</h1>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Restoran</th>
                            <th>Kupon</th>
                            <th>İndirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($coupons)): ?>
                            <?php foreach ($coupons as $coupon): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($coupon['restaurant_name'] ?: 'Genel'); ?></td>
                                    <td><?php echo htmlspecialchars($coupon['name']); ?></td>
                                    <td><?php echo htmlspecialchars('%' . intval($coupon['discount'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Kupon bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php require 'layout/footer.php' ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
