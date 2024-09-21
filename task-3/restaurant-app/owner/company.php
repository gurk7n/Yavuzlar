<?php
require '../db.php';
require 'query/check.php';

$user_id = $_SESSION['user_id'] ?? null; 
$company = null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $company_id = $user['company_id'] ?? null;

    if ($company_id) {
        $stmt = $pdo->prepare("SELECT * FROM company WHERE id = :company_id LIMIT 1");
        $stmt->execute(['company_id' => $company_id]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
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
                    <h1 class="my-4">Firma Yönetimi</h1> 
                    <form method="POST" action="query/company-save.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($company['id'] ?? ''); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Firma Adı</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($company['name'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($company['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Mevcut Logo</label>
                            <?php if (!empty($company['logo_path'])): ?>
                                <div>
                                    <img src="<?php echo '../images/company/' . htmlspecialchars($company['logo_path']); ?>" alt="Firma Logosu" style="max-width: 150px;">
                                </div>
                            <?php endif; ?>
                            <label for="logo" class="form-label">Yeni Logo Yükle</label>
                            <input type="file" class="form-control" id="logo" name="logo">
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
