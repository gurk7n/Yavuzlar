<?php
require '../db.php';
require 'query/check.php';

$sql = "SELECT company_id FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user_company_id = $stmt->fetchColumn();

$search = $_GET['search'] ?? '';

$sql = "SELECT c.*, u.username as user_name, r.name as restaurant_name 
        FROM comments c
        LEFT JOIN users u ON c.user_id = u.id
        LEFT JOIN restaurant r ON c.restaurant_id = r.id
        WHERE r.company_id = :company_id 
        AND (c.title LIKE :search OR c.description LIKE :search)
        AND c.deleted_at IS NULL
        ORDER BY c.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'company_id' => $user_company_id,
    'search' => "%$search%"
]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Müşteri Yorumları</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php require 'layout/sidebar.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="my-4">Müşteri Yorumları</h1>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($comment['id']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['restaurant_name']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['title']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['description']); ?></td>
                                    <td><?php echo htmlspecialchars(intval($comment['score']) . '/10'); ?></td>
                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($comment['created_at']))); ?></td>
                                    <td><?php echo htmlspecialchars($comment['updated_at'] ? date('d-m-Y', strtotime($comment['updated_at'])) : ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Yorum bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php require '../layout/footer.php' ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>
</html>
