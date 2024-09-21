<?php
require '../db.php';
require 'layout/check.php';

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT * FROM company WHERE name LIKE :search";

if ($filter === 'active') {
    $sql .= " AND deleted_at IS NULL";
} elseif ($filter === 'deleted') {
    $sql .= " AND deleted_at IS NOT NULL";
}

$sql .= " ORDER BY id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$firms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Firma Yönetimi</title>
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
                        <h1 class="my-0">Firma Yönetimi</h1>
                        <a href="company-add.php" class="btn btn-success">Firma Ekle</a>
                    </div>
                    <form class="d-flex mb-4" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Firma Adı Ara" value="<?php echo htmlspecialchars($search); ?>">
                        <select class="form-select me-2" name="filter">
                            <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Hepsi</option>
                            <option value="active" <?php echo $filter == 'active' ? 'selected' : ''; ?>>Aktif Firmalar</option>
                            <option value="deleted" <?php echo $filter == 'deleted' ? 'selected' : ''; ?>>Pasif Firmalar</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Ara</button>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Firma Adı</th>
                                <th>Açıklama</th>
                                <th>Silinme Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($firms)): ?>
                                <?php foreach ($firms as $firm): ?>
                                    <tr class="<?php echo $firm['deleted_at'] ? 'deleted-row' : ''; ?>">
                                        <td><?php echo htmlspecialchars($firm['id']); ?></td>
                                        <td>
                                            <?php if (!empty($firm['logo_path'])): ?>
                                                <img src="../images/company/<?php echo htmlspecialchars($firm['logo_path']); ?>" alt="Logo" width="50">
                                            <?php else: ?>
                                                <span>Logo yok</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($firm['name']); ?></td>
                                        <td><?php echo htmlspecialchars($firm['description']); ?></td>
                                        <td><?php echo $firm['deleted_at'] ? htmlspecialchars(date('d-m-Y', strtotime($firm['deleted_at']))) : ''; ?></td>
                                        <td>
                                            <?php if ($firm['deleted_at']): ?>
                                                <a href="query/company-activate.php?id=<?php echo $firm['id']; ?>" class="btn btn-success btn-sm">Aktifleştir</a>
                                            <?php else: ?>
                                                <a href="company-edit.php?id=<?php echo $firm['id']; ?>" class="btn btn-primary btn-sm">Düzenle</a>
                                                <a href="query/company-delete.php?id=<?php echo $firm['id']; ?>" class="btn btn-danger btn-sm">Sil</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Firma bulunamadı.</td>
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