<?php
require 'db.php';
require 'query/check.php';

$restaurantId = $_GET['id'];

$stmtRestaurant = $pdo->prepare("SELECT name FROM restaurant WHERE id = :id AND deleted_at IS NULL");
$stmtRestaurant->execute([':id' => $restaurantId]);
$restaurant = $stmtRestaurant->fetch(PDO::FETCH_ASSOC);

$stmtFoods = $pdo->prepare("SELECT id, name, description, image_path, price, discount FROM food WHERE restaurant_id = :restaurant_id AND deleted_at IS NULL");
$stmtFoods->execute([':restaurant_id' => $restaurantId]);
$foods = $stmtFoods->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - <?php echo htmlspecialchars($restaurant['name']); ?></title> 
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="my-4"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
        
        <div class="row">
          <?php foreach ($foods as $food): ?>
            <div class="col-md-3 mb-4">
              <div class="card h-100">
                <img src="/images/food/<?php echo htmlspecialchars($food['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($food['name']); ?>" style="height: 150px; object-fit: cover;">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($food['description']); ?></p>
                  <p class="card-text">Fiyat: <?php echo number_format($food['price'], 2); ?>₺</p>
                  <?php if ($food['discount']): ?>
                    <p class="card-text text-danger">İndirim: %<?php echo htmlspecialchars($food['discount']); ?></p>
                  <?php endif; ?>
                  <form method="get" action="query/add_to_cart.php">
                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" style="width: 80px;">
                    <input type="text" name="note" placeholder="Not (opsiyonel)" class="my-2">
                    <button class="btn btn-primary" type="submit">Sepete Ekle</button>
                  </form>
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
