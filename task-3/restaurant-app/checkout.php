<?php
require 'db.php';
require 'query/check.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($userId && !empty($_POST['basket_id']) && !empty($_POST['quantity'])) {
    $basketItems = $_POST['basket_id'];
    $quantities = $_POST['quantity'];
    $notes = isset($_POST['note']) ? $_POST['note'] : [];
    $couponCode = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : null;

    $totalPrice = 0;
    $orderItemsData = [];

    foreach ($basketItems as $basketId) {
        $stmtBasket = $pdo->prepare("SELECT food_id FROM basket WHERE id = :basket_id AND user_id = :user_id");
        $stmtBasket->execute([':basket_id' => $basketId, ':user_id' => $userId]);
        $basketItem = $stmtBasket->fetch(PDO::FETCH_ASSOC);

        if ($basketItem) {
            $stmtFood = $pdo->prepare("SELECT price, restaurant_id FROM food WHERE id = :food_id");
            $stmtFood->execute([':food_id' => $basketItem['food_id']]);
            $food = $stmtFood->fetch(PDO::FETCH_ASSOC);

            if ($food) {
                $quantity = $quantities[$basketId];
                $price = $food['price'];
                $note = isset($notes[$basketId]) ? $notes[$basketId] : null;
                $totalPrice += $price * $quantity;

                $orderItemsData[] = [
                    'food_id' => $basketItem['food_id'],
                    'restaurant_id' => $food['restaurant_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'note' => $note
                ];
            }
        }
    }

    if ($couponCode) {
        $stmtCoupon = $pdo->prepare("SELECT * FROM cupon WHERE name = :name AND deleted_at IS NULL");
        $stmtCoupon->execute([':name' => $couponCode]);
        $coupon = $stmtCoupon->fetch(PDO::FETCH_ASSOC);

        if ($coupon) {
            $couponRestaurantId = $coupon['restaurant_id'];
            $couponDiscount = $coupon['discount'];

            $isCouponValid = false;

            if ($couponRestaurantId === null) {
                $isCouponValid = true;
            } else {
                foreach ($orderItemsData as $item) {
                    if ($item['restaurant_id'] == $couponRestaurantId) {
                        $isCouponValid = true;
                        break;
                    }
                }
            }

            if ($isCouponValid) {
                $totalPrice = $totalPrice - ($totalPrice * ($couponDiscount / 100));
            } else {
                echo json_encode(['success' => false, 'message' => 'Kupon bu sipariş için geçerli değil.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz kupon kodu.']);
            exit;
        }
    }

    $stmtUser = $pdo->prepare("SELECT balance FROM users WHERE id = :user_id");
    $stmtUser->execute([':user_id' => $userId]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['balance'] >= $totalPrice) {
            $newBalance = $user['balance'] - $totalPrice;

            $stmtOrder = $pdo->prepare("INSERT INTO `order` (user_id, total_price, order_status) VALUES (:user_id, :total_price, 'Bekleniyor')");
            $stmtOrder->execute([
                ':user_id' => $userId,
                ':total_price' => $totalPrice
            ]);

            $orderId = $pdo->lastInsertId();

            $stmtOrderItems = $pdo->prepare("INSERT INTO order_items (food_id, order_id, quantity, price, note) VALUES (:food_id, :order_id, :quantity, :price, :note)");

            foreach ($orderItemsData as $item) {
                $stmtOrderItems->execute([
                    ':food_id' => $item['food_id'],
                    ':order_id' => $orderId,
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                    ':note' => $item['note']
                ]);
            }

            $stmtUpdateBalance = $pdo->prepare("UPDATE users SET balance = :balance WHERE id = :user_id");
            $stmtUpdateBalance->execute([
                ':balance' => $newBalance,
                ':user_id' => $userId
            ]);

            $stmtClearBasket = $pdo->prepare("DELETE FROM basket WHERE user_id = :user_id");
            $stmtClearBasket->execute([':user_id' => $userId]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Yetersiz bakiye.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Kullanıcı bulunamadı.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Sepetiniz boş veya oturum bulunamadı.']);
}
