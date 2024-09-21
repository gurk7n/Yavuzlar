<?php
require 'db.php';
require 'query/check.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($userId) {
    $stmtBasket = $pdo->prepare("SELECT id, food_id, note, quantity FROM basket WHERE user_id = :user_id");
    $stmtBasket->execute([':user_id' => $userId]);
    $basketItems = $stmtBasket->fetchAll(PDO::FETCH_ASSOC);

    $foodDetails = [];
    foreach ($basketItems as $item) {
        $stmtFood = $pdo->prepare("SELECT name, description, image_path, price, discount FROM food WHERE id = :food_id");
        $stmtFood->execute([':food_id' => $item['food_id']]);
        $foodDetails[$item['food_id']] = $stmtFood->fetch(PDO::FETCH_ASSOC);
    }
} else {
    echo 'Oturum bulunamadı.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Sepetim</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <script>
    function updateTotal() {
      const quantities = document.querySelectorAll('input[name^="quantity"]');
      const totalElement = document.getElementById('total');
      let total = 0;

      quantities.forEach(input => {
        const quantity = parseFloat(input.value) || 0;
        const priceElement = input.closest('.card-body').querySelector('.price');
        const price = parseFloat(priceElement.dataset.price) || 0;
        total += quantity * price;
      });

      totalElement.textContent = total.toFixed(2) + '₺';
    }

    async function removeItem(basketId, itemElement) {
      const response = await fetch('query/remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `remove=${basketId}`
      });

      if (response.ok) {
        itemElement.remove();
        updateTotal();
      }
    }

    document.addEventListener('input', updateTotal);
    document.addEventListener('DOMContentLoaded', updateTotal);
  </script>
</head>
<body class="sb-nav-fixed">
  <?php require 'layout/sidebar.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center my-4">
          <h1 class="my-0">Sepetim</h1>
        </div>

        <?php if (!empty($basketItems)): ?>
          <form method="post" action="checkout.php" id="basketForm">
            <div class="row">
              <?php foreach ($basketItems as $item): ?>
                <?php if (isset($foodDetails[$item['food_id']])): ?>
                  <?php $food = $foodDetails[$item['food_id']]; ?>
                  <div class="col-md-4 mb-4 item" id="item-<?php echo $item['id']; ?>">
                    <div class="card h-100">
                      <img src="/images/food/<?php echo htmlspecialchars($food['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($food['name']); ?>" style="height: 150px; object-fit: cover;">
                      <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($food['description']); ?></p>
                        <p class="card-text price" data-price="<?php echo number_format($food['price'], 2); ?>">Fiyat: <?php echo number_format($food['price'], 2); ?>₺</p>
                        <?php if ($food['discount']): ?>
                          <p class="card-text text-danger">İndirim: %<?php echo htmlspecialchars($food['discount']); ?></p>
                        <?php endif; ?>
                        <div class="d-flex align-items-center">
                          <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" style="width: 80px;" class="form-control me-2">
                          <input type="text" name="note[<?php echo $item['id']; ?>]" value="<?php echo htmlspecialchars($item['note']); ?>" placeholder="Not (opsiyonel)" class="form-control me-2">
                          <input type="hidden" name="food_id[]" value="<?php echo htmlspecialchars($item['food_id']); ?>">
                          <input type="hidden" name="basket_id[]" value="<?php echo htmlspecialchars($item['id']); ?>">
                          <button type="button" class="btn btn-danger" onclick="removeItem(<?php echo $item['id']; ?>, document.getElementById('item-<?php echo $item['id']; ?>'))">Kaldır</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <div class="d-flex align-items-center justify-content-start">
              <h5 class="me-3">Toplam Tutar: <span id="total">0.00₺</span></h5>
              <button class="btn btn-primary" type="submit">Siparişi Onayla</button>
            </div>
          </form>
        <?php else: ?>
          <p>Sepetinizde hiç ürün bulunmuyor.</p>
        <?php endif; ?>
      </div>
    </main>
    <?php require 'layout/footer.php'; ?>
  </div>
  <script>
  async function confirmOrder() {
    const couponCode = prompt("Kupon kodu varsa buraya girin (opsiyonel):");

    if (couponCode !== null) {
      const formData = new FormData(document.getElementById('basketForm'));
      formData.append('coupon_code', couponCode);

      const response = await fetch('checkout.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert('Sipariş başarıyla oluşturuldu!');
        window.location.href = 'orders.php';
      } else {
        alert(result.message);
      }
    }
  }

  document.querySelector('form#basketForm').addEventListener('submit', function (e) {
    e.preventDefault();
    confirmOrder();
  });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="../js/scripts.js"></script>
</body>
</html>
