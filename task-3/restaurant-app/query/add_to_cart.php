<?php
require '../db.php';
require 'check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Çıkış Yap</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($userId && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $foodId = isset($_GET['food_id']) ? (int)$_GET['food_id'] : 0;
    $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
    $note = isset($_GET['note']) ? $_GET['note'] : null;

    if ($foodId > 0) {
        try {
            $stmtFood = $pdo->prepare("SELECT restaurant_id FROM food WHERE id = :food_id");
            $stmtFood->execute([':food_id' => $foodId]);
            $newFoodRestaurant = $stmtFood->fetch(PDO::FETCH_ASSOC)['restaurant_id'];

            $stmtBasket = $pdo->prepare("SELECT basket.id, food.restaurant_id 
                                         FROM basket 
                                         INNER JOIN food ON basket.food_id = food.id 
                                         WHERE basket.user_id = :user_id");
            $stmtBasket->execute([':user_id' => $userId]);
            $basketItems = $stmtBasket->fetchAll(PDO::FETCH_ASSOC);

            $differentRestaurant = false;
            foreach ($basketItems as $item) {
                if ($item['restaurant_id'] !== $newFoodRestaurant) {
                    $differentRestaurant = true;
                    $stmtDelete = $pdo->prepare("DELETE FROM basket WHERE id = :id");
                    $stmtDelete->execute([':id' => $item['id']]);
                }
            }

            $stmtInsert = $pdo->prepare("INSERT INTO basket (user_id, food_id, note, quantity, created_at) 
                                         VALUES (:user_id, :food_id, :note, :quantity, NOW())");
            $stmtInsert->execute([
                ':user_id' => $userId,
                ':food_id' => $foodId,
                ':note' => $note,
                ':quantity' => $quantity
            ]);

            if ($differentRestaurant) {
                echo '<script>
                    Swal.fire({
                        icon: "info",
                        title: "Sepet Güncellendi",
                        text: "Sepetinizde başka bir restorana ait ürünler kaldırıldı ve yeni ürün eklendi.",
                        confirmButtonText: "Tamam"
                    }).then(() => {
                        window.location.href = "../basket.php";
                    });
                </script>';
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Başarılı",
                        text: "Yemek sepete eklendi!",
                        confirmButtonText: "Tamam"
                    }).then(() => {
                        window.location.href = "../basket.php";
                    });
                </script>';
            }

        } catch (PDOException $e) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Hata",
                    text: "Bir hata oluştu: ' . $e->getMessage() . '",
                    confirmButtonText: "Tamam"
                });
            </script>';
        }
    } else {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Hata",
                text: "Geçersiz yemek ID!",
                confirmButtonText: "Tamam"
            });
        </script>';
    }
} else {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Hata",
            text: "Geçersiz istek veya oturum!",
            confirmButtonText: "Tamam"
        });
    </script>';
}
?>
</body>
</html>
