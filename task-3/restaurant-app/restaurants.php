<?php
require 'db.php';
require 'query/check.php';

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $pdo->prepare("SELECT id, name, description, image_path FROM restaurant WHERE deleted_at IS NULL AND name LIKE :searchTerm");
$stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Restoranlar</title> 
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="my-4">Restoranlar</h1>

        <form class="mb-4" method="get" action="">
          <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Restoran ara" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn btn-primary" type="submit">Ara</button>
          </div>
        </form>
        
        <div class="row">
          <?php foreach ($restaurants as $restaurant): ?>
            <div class="col-12 col-md-3 mb-4">
              <div class="card h-100">
                <div class="row g-0">
                  <div class="col-md-12 d-flex align-items-center justify-content-center py-3">
                    <img src="/images/restaurant/<?php echo $restaurant['image_path']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($restaurant['name']); ?>" style="width: 150px; height: 100px; object-fit: cover;">
                  </div>
                  <div class="col-md-12">
                    <div class="card-body">
                      <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                      <p class="card-text"><?php echo htmlspecialchars($restaurant['description']); ?></p>
                      <?php
                      $stmtScore = $pdo->prepare("SELECT AVG(score) AS avg_score FROM comments WHERE restaurant_id = :restaurant_id AND deleted_at is NULL");
                      $stmtScore->execute([':restaurant_id' => $restaurant['id']]);
                      $scoreData = $stmtScore->fetch(PDO::FETCH_ASSOC);
                      $avgScore = $scoreData['avg_score'];
                      if ($avgScore) {
                          echo "<p class='card-text'>Puan: " . number_format($avgScore, 1) . "/10</p>";
                      } else {
                          echo "<p class='card-text'>Puan yok</p>";
                      }
                      ?>
                      <div class="d-flex">
                        <a href="menu.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-primary me-2">Sipariş Ver</a>
                        <a href="comments.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-secondary">Yorumlar</a>
                      </div>
                    </div>
                  </div>
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
  <script src="../js/scripts.js"></script>
</body>
</html>
