<?php
require '../db.php';
require 'query/check.php';

$sql = "SELECT company_id FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user_company_id = $stmt->fetchColumn();

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT r.*, c.name as company_name FROM restaurant r
        LEFT JOIN company c ON r.company_id = c.id
        WHERE r.company_id = :company_id
        AND r.name LIKE :search";

if ($filter === 'active') {
    $sql .= " AND r.deleted_at IS NULL";
} elseif ($filter === 'deleted') {
    $sql .= " AND r.deleted_at IS NOT NULL";
}

$sql .= " ORDER BY r.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'company_id' => $user_company_id,
    'search' => "%$search%"
]);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Yönetimi</title>
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
                <h1 class="my-0">Restoran Yönetimi</h1>
                <a href="restaurant-add.php" class="btn btn-success">Restoran Ekle</a>
            </div>
                <form class="d-flex mb-4" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Restoran Adı Ara" value="<?php echo htmlspecialchars($search); ?>">
                    <select class="form-select me-2" name="filter">
                        <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Hepsi</option>
                        <option value="active" <?php echo $filter == 'active' ? 'selected' : ''; ?>>Aktif Restoranlar</option>
                        <option value="deleted" <?php echo $filter == 'deleted' ? 'selected' : ''; ?>>Pasif Restoranlar</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Ara</button>
                </form>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Firma Adı</th>
                            <th>Resim</th>
                            <th>Restoran Adı</th>
                            <th>Açıklama</th>
                            <th>Kayıt Tarihi</th>
                            <th>Silinme Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($restaurants)): ?>
                            <?php foreach ($restaurants as $restaurant): ?>
                                <tr class="<?php echo $restaurant['deleted_at'] ? 'deleted-row' : ''; ?>">
                                    <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                                    <td><?php echo htmlspecialchars($restaurant['company_name']); ?></td>
                                    <td>
                                        <?php if (!empty($restaurant['image_path'])): ?>
                                            <img src="../images/restaurant/<?php echo htmlspecialchars($restaurant['image_path']); ?>" alt="Resim" width="50">
                                        <?php else: ?>
                                            <span>Resim yok</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                                    <td><?php echo htmlspecialchars($restaurant['description']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($restaurant['created_at']))); ?></td>
                                    <td><?php echo $restaurant['deleted_at'] ? htmlspecialchars(date('d-m-Y', strtotime($restaurant['deleted_at']))) : ''; ?></td>
                                    <td>
                                        <?php if ($restaurant['deleted_at']): ?>
                                            <a href="query/restaurant-activate.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-success btn-sm">Aktifleştir</a>
                                        <?php else: ?>
                                            <a href="restaurant-edit.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-primary btn-sm">Düzenle</a>
                                            <a href="query/restaurant-delete.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-danger btn-sm">Sil</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Restoran bulunamadı.</td>
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
