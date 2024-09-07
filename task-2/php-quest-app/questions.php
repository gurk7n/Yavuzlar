<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok!";
    exit;
}

include 'db.php';

$searchTerm = '';
$query = "SELECT * FROM sorular";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query .= " WHERE soru LIKE :search";
}

$stmt = $db->prepare($query);

if (!empty($searchTerm)) {
    $stmt->bindValue(':search', '%' . $searchTerm . '%');
}

$stmt->execute();
$sorular = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_stmt = $db->prepare("DELETE FROM sorular WHERE id = :id");
    $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $delete_stmt->execute();

    header("Location: questions.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/questions.css" />
    <title>Quest App - Sorular</title>
</head>
<body>
    <h1 class="topTitle">Sorular</h1>
    <div class="searchBox">
        <a href="index.php"><button class="geriBtn">Geri Dön</button></a>
        <form method="GET" action="questions.php">
            <input type="search" class="searchInp" name="search" placeholder="Soru.." value="<?php echo htmlspecialchars($searchTerm); ?>" />
            <button type="submit" class="searchBtn">Ara</button>
        </form>
    </div>

    <?php if (empty($sorular)): ?>
        <p>Soru bulunamadı!</p>
    <?php else: ?>
        <?php foreach ($sorular as $soru): ?>
            <div class='questBox'>
                <?php echo htmlspecialchars($soru['soru']); ?>
                <div class='buttons'>
                    <form action='edit-question.php' method='post' style='display:inline;'>
                        <input type='hidden' name='edit_id' value='<?php echo htmlspecialchars($soru['id']); ?>' />
                        <button class='editBtn' type='submit'>Düzenle</button>
                    </form>
                    <form action='questions.php' method='post' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='<?php echo htmlspecialchars($soru['id']); ?>' />
                        <button class='delBtn' type='submit'>Sil</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
