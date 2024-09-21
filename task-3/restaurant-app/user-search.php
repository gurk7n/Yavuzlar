<?php
require 'db.php';
require 'query/check.php';

$search_query = $_POST['search_query'] ?? '';

$query = $pdo->prepare("SELECT * FROM users WHERE (name LIKE :search OR surname LIKE :search OR username LIKE :search) AND deleted_at IS NULL");
$query->execute(['search' => '%' . $search_query . '%']);
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Kullanıcı Arama</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <style>
    .card img {
      max-height: 150px;
      object-fit: cover;
    }
    .card {
      max-width: 18rem;
    }
  </style>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center my-4">
          <h1 class="my-0">Arama Sonuçları</h1>
        </div>
        <div class="row">
          <?php foreach ($users as $user): ?>
            <?php
            $image_path = $user['image_path'] ? 'images/user/' . $user['image_path'] : 'images/user/default.jpg';
            ?>
            <div class="col-md-3 col-sm-6">
              <div class="card mb-4">
                <img src="<?php echo $image_path; ?>" class="card-img-top img-fluid" alt="Profil Resmi">
                <div class="card-body text-center">
                  <h5 class="card-title"><?php echo htmlspecialchars($user['username']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
                  <a href="user-profile.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">Profili Gör</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
    <?php require 'layout/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
