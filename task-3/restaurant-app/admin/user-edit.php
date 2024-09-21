<?php
require '../db.php';
require 'layout/check.php';

$user = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$imagePath = '../images/user/' . ($user['image_path'] ?? 'default.jpg');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kullanıcı Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Kullanıcı Düzenle</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="users.php">Kullanıcı Yönetimi</a></li>
                        <li class="breadcrumb-item active">Kullanıcı Düzenle</li>
                    </ol>

                    <form method="POST" action="query/user-save.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>">

                        <div class="mb-3">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="User Image" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Yeni Resim Yükle</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>

                        <div class="mb-3">
                            <label for="company_id" class="form-label">Firma ID</label>
                            <input type="number" class="form-control" id="company_id" name="company_id" value="<?php echo htmlspecialchars($user['company_id'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select class="form-select" id="role" name="role">
                                <option value="admin" <?php echo (isset($user['role']) && $user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="owner" <?php echo (isset($user['role']) && $user['role'] === 'owner') ? 'selected' : ''; ?>>Firma Sahibi</option>
                                <option value="user" <?php echo (isset($user['role']) && $user['role'] === 'user') ? 'selected' : ''; ?>>Müşteri</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Ad</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="surname" class="form-label">Soyad</label>
                            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Yeni Şifre</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="balance" class="form-label">Bakiye</label>
                            <input type="text" class="form-control" id="balance" name="balance" value="<?php echo htmlspecialchars($user['balance'] ?? ''); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </form>
                </div>
            </main>
        <?php require 'layout/footer.php' ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
