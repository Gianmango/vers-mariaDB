<?php
session_start();
include("db.php");

// Verifica se l'utente è loggato e admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    echo "<p>Accesso negato. Questa pagina è riservata agli amministratori.</p>";
    exit;
}

// Aggiunta magazzino
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aggiungi"])) {
    $posizione = trim($_POST["posizione"]);
    if ($posizione != "") {
        $stmt = $conn->prepare("INSERT INTO magazzini (posizione) VALUES (?)");
        $stmt->bind_param("s", $posizione);
        $stmt->execute();
        echo "<p>✅ Magazzino aggiunto.</p>";
    }
}

// Eliminazione magazzino
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["elimina"])) {
    $id = intval($_POST["magazzinoID"]);
    $stmt = $conn->prepare("DELETE FROM magazzini WHERE magazzinoID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<p>🗑️ Magazzino eliminato.</p>";
}
?>

<h2>Gestione Magazzini (Admin)</h2>

<h3>📍 Aggiungi Magazzino</h3>
<form method="POST">
    Posizione: <input type="text" name="posizione" required>
    <input type="submit" name="aggiungi" value="Aggiungi">
</form>

<hr>

<h3>📦 Magazzini Esistenti</h3>
<ul>
    <?php
    $result = $conn->query("SELECT * FROM magazzini");
    while ($row = $result->fetch_assoc()) {
        echo "<li>
                ID: {$row['magazzinoID']} - <strong>{$row['posizione']}</strong>
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='magazzinoID' value='{$row['magazzinoID']}'>
                    <input type='submit' name='elimina' value='Elimina' onclick='return confirm(\"Sei sicuro?\")'>
                </form>
              </li>";
    }
    ?>
</ul>

<a href="prodotti.php">🔙 Torna alla gestione prodotti</a>
