<?php
session_start();
include("db.php");

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    echo "<p>Accesso negato. Questa pagina è riservata agli amministratori.</p>";
    exit;
}

// Aggiunta magazzino (VULNERABILE A SQL INJECTION)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aggiungi"])) {
    $posizione = $_POST["posizione"];
    $query = "INSERT INTO magazzini (posizione) VALUES ('$posizione')";
    $conn->query($query);
    $msg = "✅ Magazzino aggiunto.";
}

// Eliminazione magazzino (VULNERABILE A SQL INJECTION)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["elimina"])) {
    $id = $_POST["magazzinoID"];
    $query = "DELETE FROM magazzini WHERE magazzinoID = $id";
    $conn->query($query);
    $msg = "🗑️ Magazzino eliminato.";
}

$result = $conn->query("SELECT * FROM magazzini");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Magazzini</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Gestione Magazzini (Admin)</h2>

        <?php if (isset($msg)) echo "<p>$msg</p>"; ?>

        <h3>📍 Aggiungi Magazzino</h3>
        <form method="POST">
            Posizione: <input type="text" name="posizione" required>
            <input type="submit" name="aggiungi" value="Aggiungi">
        </form>

        <hr>

        <h3>📦 Magazzini Esistenti</h3>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    ID: <?= $row['magazzinoID'] ?> - <strong><?= htmlspecialchars($row['posizione']) ?></strong>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="magazzinoID" value="<?= $row['magazzinoID'] ?>">
                        <input type="submit" name="elimina" value="Elimina" onclick="return confirm('Sei sicuro?')">
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>

        <a href="prodotti.php">🔙 Torna alla gestione prodotti</a>
    </div>
</body>
</html>
