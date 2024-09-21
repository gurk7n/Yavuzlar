<?php
require '../db.php';
require 'layout/check.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT c.*, u.username as user_name, r.name as restaurant_name 
        FROM comments c
        LEFT JOIN users u ON c.user_id = u.id
        LEFT JOIN restaurant r ON c.restaurant_id = r.id
        WHERE c.title LIKE :search OR c.description LIKE :search
        ORDER BY c.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    if (isset($_POST['sil'])) {
        $stmt = $pdo->prepare("UPDATE comments SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif (isset($_POST['aktif'])) {
        $stmt = $pdo->prepare("UPDATE comments SET deleted_at = NULL WHERE id = :id");
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
    <title>Restoran - Yorum Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="my-4">Yorum Yönetimi</h1>
                <form class="d-flex mb-4" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Yorum Ara" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">Ara</button>
                </form>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı</th>
                            <th>Restoran</th>
                            <th>Başlık</th>
                            <th>Yorum</th>
                            <th>Puan</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Güncelleme Tarihi</th>
                            <th>Silinme Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                <tr class="<?php echo $comment['deleted_at'] ? 'deleted-row' : ''; ?>">
                                    <td><?php echo htmlspecialchars($comment['id']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['restaurant_name'] ?: 'Genel'); ?></td>
                                    <td><?php echo htmlspecialchars($comment['title']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['description']); ?></td>
                                    <td><?php echo htmlspecialchars(intval($comment['score']) . '/10'); ?></td>
                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($comment['created_at']))); ?></td>
                                    <td><?php echo htmlspecialchars($comment['updated_at'] ? date('d-m-Y', strtotime($comment['updated_at'])) : ''); ?></td>
                                    <td><?php echo htmlspecialchars($comment['deleted_at'] ? date('d-m-Y', strtotime($comment['deleted_at'])) : ''); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                            <?php if ($comment['deleted_at'] === null): ?>
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
                                <td colspan="10" class="text-center">Yorum bulunamadı.</td>
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
