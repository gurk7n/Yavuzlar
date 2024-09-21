<?php
require '../db.php';
require 'query/check.php';

$restaurant = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM restaurant WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
}

$companies = $pdo->query("SELECT id, name FROM company")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Restoran Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Restoran Düzenle</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="restaurants.php">Restoran Yönetimi</a></li>
                        <li class="breadcrumb-item active">Restoran Düzenle</li>
                    </ol>

                    <form method="POST" action="query/restaurant-save.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($restaurant['id'] ?? ''); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Restoran Adı</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($restaurant['name'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Mevcut Görsel</label>
                            <?php if (!empty($restaurant['image_path'])): ?>
                                <div>
                                    <img src="<?php echo '../images/restaurant/' . htmlspecialchars($restaurant['image_path']); ?>" alt="Restoran Görseli" style="max-width: 150px;">
                                </div>
                            <?php endif; ?>
                            <label for="image" class="form-label">Yeni Görsel Yükle</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>

                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </form>
                </div>
            </main>
            <?php require '../layout/footer.php' ?>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
