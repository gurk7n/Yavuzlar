-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: db:3306
-- Üretim Zamanı: 18 Eyl 2024, 13:45:33
-- Sunucu sürümü: 9.0.1
-- PHP Sürümü: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restoran`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `basket`
--

CREATE TABLE `basket` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `food_id` int DEFAULT NULL,
  `note` text,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `restaurant_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `score` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `restaurant_id`, `title`, `description`, `score`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 1, 'Hızlı Teslimat, Orta Lezzet', 'Sipariş hızlı geldi, fakat lezzet beklentimin biraz altında kaldı', 7, '2024-09-12 09:07:03', '2024-09-12 16:07:16', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `logo_path` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `company`
--

INSERT INTO `company` (`id`, `name`, `description`, `logo_path`, `deleted_at`) VALUES
(1, 'HD Holding', 'Türkiye\'de restoran ve gıda sektöründe öncü, büyük bir şirkettir.', 'hd-holding.jpg', NULL),
(2, 'Pardo Food', 'Lezzetli Türk mutfağıyla bilinen, kaliteli restoranlar zinciridir.', 'pardo-food.jpg', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cupon`
--

CREATE TABLE `cupon` (
  `id` int NOT NULL,
  `restaurant_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `cupon`
--

INSERT INTO `cupon` (`id`, `restaurant_id`, `name`, `discount`, `created_at`, `deleted_at`) VALUES
(1, NULL, 'EYLUL35', 35.00, '2024-09-11 17:03:41', NULL),
(2, 2, 'TAVUK20', 20.00, '2024-09-12 12:48:38', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `food`
--

CREATE TABLE `food` (
  `id` int NOT NULL,
  `restaurant_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `food`
--

INSERT INTO `food` (`id`, `restaurant_id`, `name`, `description`, `image_path`, `price`, `discount`, `created_at`, `deleted_at`) VALUES
(1, 1, 'İskender', 'Özel soslu ve tereyağlı İskender, taze yoğurt ve pidelerle servis edilir.', 'iskender.jpg', 280.00, NULL, '2024-09-11 18:00:02', NULL),
(2, 2, 'Kremantar', 'Kremalı mantar soslu tavuk, özel soslu makarna ve Akdeniz salatası ile.', 'kremantar.jpg', 300.00, NULL, '2024-09-13 22:01:22', NULL),
(3, 2, 'Mercimek Çorbası', 'Mercimek çorbası, salata ve limon dilimi ile sunulur.', 'mercimek.jpg', 80.00, NULL, '2024-09-13 22:02:40', NULL),
(4, 2, 'Sütlaç', 'Fırında hafifçe kızarmış, bol fındıklı klasik sütlaç.', 'sutlac.jpg', 100.00, NULL, '2024-09-13 22:12:04', NULL),
(5, 2, 'Coca Cola', 'Ferahlatıcı Coca Cola, her yudumda tazelik.', 'kola.jpg', 60.00, NULL, '2024-09-13 22:14:51', NULL),
(6, 1, 'Mevsim Salata', 'Mevsim sebzeleriyle taze ve hafif bir salata.', 'salata.jpg', 180.00, NULL, '2024-09-14 06:01:03', NULL),
(7, 1, 'Patates Kızartması ve Turşu', 'Kızarmış patates ve kornişon turşu ile servis edilir.', 'tursu.png', 110.00, NULL, '2024-09-14 06:01:44', NULL),
(8, 1, 'İçim Ayran', 'İçim ayran, ferahlatıcı ve doğal lezzetiyle.', 'ayran.jpg', 40.00, NULL, '2024-09-14 06:02:43', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `order`
--

INSERT INTO `order` (`id`, `user_id`, `order_status`, `total_price`, `created_at`) VALUES
(1, 4, 'Teslim Edildi', 300.00, '2024-09-11 10:53:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `food_id` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `order_items`
--

INSERT INTO `order_items` (`id`, `food_id`, `order_id`, `quantity`, `price`, `note`) VALUES
(1, 1, 1, 1, 300.00, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `restaurant`
--

CREATE TABLE `restaurant` (
  `id` int NOT NULL,
  `company_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `restaurant`
--

INSERT INTO `restaurant` (`id`, `company_id`, `name`, `description`, `image_path`, `created_at`, `deleted_at`) VALUES
(1, 1, 'HD İskender', 'HD İskender, İskender kebabıyla ünlü bir markadır.', 'hd-iskender.jpg', '2024-09-10 11:39:58', NULL),
(2, 2, 'Tavuk Dünyası', 'Tavuk Dünyası, soslu tavuk yemekleri sunar.', 'tavuk-dunyasi.jpg', '2024-09-10 11:40:52', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `company_id` int DEFAULT NULL,
  `role` enum('admin','owner','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT '5000.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image_path` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `company_id`, `role`, `name`, `surname`, `username`, `password`, `balance`, `created_at`, `deleted_at`, `image_path`) VALUES
(1, NULL, 'admin', 'Gürkan', 'Zengin', 'admin', '$argon2id$v=19$m=65536,t=4,p=1$YmxNbGYuOGJ5eTNJb0N1Vg$OJGUf2jB/2N6buuhsMgKvd+xGnamwPFQOlICi9QXPeE', 5000.00, '2024-09-09 16:16:07', NULL, NULL),
(2, 1, 'owner', 'Hakan', 'Demirağ', 'hdholding', '$argon2id$v=19$m=65536,t=4,p=1$VVUveUpVNzRBbG5uRlZONA$TdSN9AMzZBMlJ47Ab5AK6o7i3ilB+Szi5EqDvFUjog4', 5000.00, '2024-09-10 07:22:14', NULL, 'hd-holding.jpg'),
(3, 2, 'owner', 'Ahmet', ' Faralyalı', 'pardofood', '$argon2id$v=19$m=65536,t=4,p=1$ZjZpSzNkbUduRWN2S25mUQ$YrPSJnEfg0gWdf8ZU4uvLJwWY57odHfoJgMyRbGFy/o', 5000.00, '2024-09-10 07:22:25', NULL, 'pardo-food.jpg'),
(4, NULL, 'user', 'Yusuf', 'Nas', 'musteri', '$argon2id$v=19$m=65536,t=4,p=1$Um5zN3lRRGlyZnB0eS5vaA$T59pvYtCYJOeIeXgjkiJexRL+6M0ZD397VqiPBf9YRU', 5000.00, '2024-09-10 09:06:27', NULL, NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Tablo için indeksler `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Tablo için indeksler `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cupon`
--
ALTER TABLE `cupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Tablo için indeksler `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Tablo için indeksler `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_id` (`food_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Tablo için indeksler `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `cupon`
--
ALTER TABLE `cupon`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `food`
--
ALTER TABLE `food`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `basket_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food` (`id`);

--
-- Tablo kısıtlamaları `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`);

--
-- Tablo kısıtlamaları `cupon`
--
ALTER TABLE `cupon`
  ADD CONSTRAINT `cupon_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`);

--
-- Tablo kısıtlamaları `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`);

--
-- Tablo kısıtlamaları `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `food` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);

--
-- Tablo kısıtlamaları `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `restaurant_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
