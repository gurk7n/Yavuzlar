<?php
require '../db.php';
require 'layout/check.php';

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$query = "SELECT id, company_id, role, name, surname, username, balance, created_at, deleted_at, image_path FROM users WHERE (name LIKE :search OR surname LIKE :search OR username LIKE :search OR CONCAT(name, ' ', surname) LIKE :search)";
if ($filter == 'active') {
    $query .= " AND deleted_at IS NULL";
} elseif ($filter == 'deleted') {
    $query .= " AND deleted_at IS NOT NULL";
}
$query .= " ORDER BY id ASC";

try {
    $usersStmt = $pdo->prepare($query);
    $usersStmt->execute(['search' => "%$search%"]);
    $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Hata!',
                text: 'Bir hata oluştu: " . $e->getMessage() . "',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Kullanıcı Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="my-4">Kullanıcı Yönetimi</h1>

                    <form class="d-flex mb-4" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Ad, Soyad veya Kullanıcı Adı Ara" value="<?php echo htmlspecialchars($search); ?>">
                        <select class="form-select me-2" name="filter">
                            <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>Hepsi</option>
                            <option value="active" <?php echo $filter == 'active' ? 'selected' : ''; ?>>Aktif Hesaplar</option>
                            <option value="deleted" <?php echo $filter == 'deleted' ? 'selected' : ''; ?>>Pasif Hesaplar</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Ara</button>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Firma ID</th>
                                <th>Rol</th>
                                <th>Resim</th>
                                <th>Ad</th>
                                <th>Soyad</th>
                                <th>Kullanıcı Adı</th>
                                <th>Bakiye</th>
                                <th>Kayıt Tarihi</th>
                                <th>Silinme Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr class="<?php echo $user['deleted_at'] ? 'deleted-row' : ''; ?>">
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['company_id'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td><img src="<?php echo $user['image_path'] ? '../images/user/' . htmlspecialchars($user['image_path']) : '../images/user/default.jpg'; ?>" alt="Kullanıcı Resmi" width="50" height="50"></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['surname']); ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($user['balance'], 2, ',', '.')); ?>₺</td>
                                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($user['created_at']))); ?></td>
                                        <td><?php echo $user['deleted_at'] ? htmlspecialchars(date('d-m-Y', strtotime($user['deleted_at']))) : ''; ?></td>
                                        <td>
                                            <?php if ($user['deleted_at']): ?>
                                                <a href="query/user-activate.php?id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Aktifleştir</a>
                                            <?php else: ?>
                                                <a href="user-edit.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Düzenle</a>
                                                <a href="query/user-delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Sil</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">Kullanıcı bulunamadı.</td>
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
