<?php
session_start();
include("db.php");

$msg = "";

// Aggiunta magazzino con prepared statement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aggiungi"])) {
    $posizione = $_POST["posizione"];
    $stmt = $conn->prepare("INSERT INTO Magazzino (posizione) VALUES (?)");
    $stmt->bind_param("s", $posizione);
    $stmt->execute();
    $msg = "✅ Magazzino aggiunto.";
}

// Eliminazione magazzino con prepared statement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["elimina"])) {
    $id = $_POST["magazzinoID"];
    $stmt = $conn->prepare("DELETE FROM Magazzino WHERE mID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "🗑️ Magazzino eliminato.";
}

// Recupero magazzini
$result = $conn->query("SELECT * FROM Magazzino");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Magazzini</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <header>
        <h1>Gestione Magazzini</h1>
        <a href="home.php">🔙 Torna alla home</a>
    </header>

    <main>
        <section class="admin-container">
            <?php if (!empty($msg)): ?>
                <p class="msg"><?= $msg ?></p>
            <?php endif; ?>

            <div class="form-section">
                <h2>📍 Aggiungi Magazzino</h2>
                <form method="POST">
                    <input type="text" name="posizione" placeholder="Inserisci posizione" required>
                    <input type="submit" name="aggiungi" value="Aggiungi">
                </form>
            </div>

            <hr>

            <div class="list-section">
                <h2>📦 Magazzini Esistenti</h2>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>
                            <span><strong>ID:</strong> <?= $row['mID'] ?> - <strong><?= htmlspecialchars($row['posizione']) ?></strong></span>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="magazzinoID" value="<?= $row['mID'] ?>">
                                <input type="submit" name="elimina" value="Elimina" onclick="return confirm('Sei sicuro?')">
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>
