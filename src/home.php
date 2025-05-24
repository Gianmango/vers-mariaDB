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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Benvenuto, <?= htmlspecialchars($username) ?>!</h1>
        <p>Ruolo: <?= htmlspecialchars($ruolo) ?></p>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <?php if ($ruolo === 'admin'): ?>
            <div class="card-container">
                <div class="card">
                    <a href="prodotti.php">Gestione Prodotti</a>
                </div>
                <div class="card">
                    <a href="magazzini.php">Gestione Magazzini</a>
                </div>
                <div class="card">
                    <a href="cerca_prodotti.php">Vai allo Shop</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card-container">
                <div class="card">
                    <a href="cerca_prodotti.php">Vai allo Shop</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
