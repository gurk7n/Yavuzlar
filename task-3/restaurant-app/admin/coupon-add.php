<?php 
require '../db.php';
require 'layout/check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kupon Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Kupon Ekle</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="coupons.php">Kupon Yönetimi</a></li>
                    <li class="breadcrumb-item active">Kupon Ekle</li>
                </ol>

                <form method="POST" action="query/coupon-save.php">
                    <div class="mb-3">
                        <label for="restaurant_id" class="form-label">Restoran</label>
                        <select class="form-select" id="restaurant_id" name="restaurant_id">
                            <option value="">Genel</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, name FROM restaurant");
                            $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($restaurants as $restaurant) {
                                echo "<option value=\"" . htmlspecialchars($restaurant['id']) . "\">" . htmlspecialchars($restaurant['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Kupon Kodu</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">İndirim (%)</label>
                        <input type="number" class="form-control" id="discount" name="discount" min="0" max="100">
                    </div>
                    <button type="submit" class="btn btn-primary">Ekle</button>
                </form>
            </div>
        </main>
        <?php require 'layout/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
