<?php
session_start();

if (!isset($_SESSION['loggato'])) {
    header("Location: index.php");
    exit();
}

$ruolo = $_SESSION['ruolo'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <header class="page-header centered-header">
        <div class="header-content">
            <h1>Benvenuto, <?= htmlspecialchars($username) ?>!</h1>
            <p>Ruolo: <?= htmlspecialchars($ruolo) ?></p>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>


    <main class="main-content">
        <div class="admin-container">
            <?php if ($ruolo === 'admin'): ?>
                <div class="card-container">
                    <div class="product-card">
                        <a href="prodotti.php" class="admin-button">Gestione Prodotti</a>
                    </div>
                    <div class="product-card">
                        <a href="magazzini.php" class="admin-button">Gestione Magazzini</a>
                    </div>
                    <div class="product-card">
                        <a href="cerca_prodotti.php" class="admin-button">Vai allo Shop</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card-container">
                    <div class="product-card">
                        <a href="cerca_prodotti.php" class="admin-button">Vai allo Shop</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
