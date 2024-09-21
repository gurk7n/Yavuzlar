<?php
require '../db.php';
require 'query/check.php';

$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$company_id = $user['company_id'];

$search = $_GET['search'] ?? '';

$sql = "SELECT f.*, r.name as restaurant_name 
        FROM food f
        LEFT JOIN restaurant r ON f.restaurant_id = r.id
        WHERE f.name LIKE :search AND r.company_id = :company_id
        ORDER BY f.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%", 'company_id' => $company_id]);
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    if (isset($_POST['sil'])) {
        $stmt = $pdo->prepare("UPDATE food SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif (isset($_POST['aktif'])) {
        $stmt = $pdo->prepare("UPDATE food SET deleted_at = NULL WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma - Yemek Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h1 class="my-0">Yemek Yönetimi</h1>
                <a href="add-food.php" class="btn btn-success">Yemek Ekle</a>
            </div>
                <form class="d-flex mb-4" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Yemek Ara" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">Ara</button>
                </form>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Restoran</th>
                            <th>Resim</th>
                            <th>Yemek</th>
                            <th>Açıklama</th>
                            <th>Fiyat</th>
                            <th>İndirim</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Silinme Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($foods)): ?>
                            <?php foreach ($foods as $food): ?>
                                <tr class="<?php echo $food['deleted_at'] ? 'deleted-row' : ''; ?>">
                                    <td><?php echo htmlspecialchars($food['restaurant_name'] ?: 'Genel'); ?></td>
                                    <td>
                                        <?php if ($food['image_path']): ?>
                                            <img src="../images/food/<?php echo htmlspecialchars($food['image_path']); ?>" alt="Food Image" style="width: 50px; height: auto;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($food['name']); ?></td>
                                    <td><?php echo htmlspecialchars($food['description']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($food['price'], 2)); ?> TL</td>
                                    <td><?php echo htmlspecialchars($food['discount'] ? $food['discount'] . '%' : ''); ?></td>
                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($food['created_at']))); ?></td>
                                    <td><?php echo htmlspecialchars($food['deleted_at'] ? date('d-m-Y', strtotime($food['deleted_at'])) : ''); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($food['id']); ?>">
                                            <?php if ($food['deleted_at'] === null): ?>
                                                <button type="submit" name="sil" class="btn btn-danger btn-sm mb-2">Sil</button>
                                                <a href="edit_food.php?id=<?php echo htmlspecialchars($food['id']); ?>" class="btn btn-primary btn-sm">Düzenle</a>
                                            <?php else: ?>
                                                <button type="submit" name="aktif" class="btn btn-success btn-sm">Aktifleştir</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Yemek bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php require '../layout/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
