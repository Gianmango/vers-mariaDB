<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

session_start();
if (!isset($_SESSION['loggato'])) {
    header("Location: index.php");
    exit();
}

$mysqli = new mysqli($host, $user, $pass, $db);

// Sanifica e proteggi
function safe($conn, $str) {
    return $conn->real_escape_string(trim($str));
}

// Gestione Azioni
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['aggiungi'])) {
        $nome = safe($mysqli, $_POST['nome']);
        $prezzo = floatval($_POST['prezzo']);
        $magazzinoID = intval($_POST['magazzinoID']);

        // Cerca o inserisci il prodotto
        $stmt = $mysqli->prepare("SELECT pID FROM prodotti WHERE nome = ? AND prezzo = ?");
        $stmt->bind_param("sd", $nome, $prezzo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pID = $row['pID'];
        } else {
            $stmt = $mysqli->prepare("INSERT INTO prodotti (nome, prezzo) VALUES (?, ?)");
            $stmt->bind_param("sd", $nome, $prezzo);
            $stmt->execute();
            $pID = $stmt->insert_id;
        }

        // Aggiorna MagazzinoProdotti
        $stmt = $mysqli->prepare("SELECT quantita FROM MagazzinoProdotti WHERE magazzinoID = ? AND prodottoID = ?");
        $stmt->bind_param("ii", $magazzinoID, $pID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $stmt = $mysqli->prepare("UPDATE MagazzinoProdotti SET quantita = quantita + 1 WHERE magazzinoID = ? AND prodottoID = ?");
            $stmt->bind_param("ii", $magazzinoID, $pID);
            $stmt->execute();
        } else {
            $stmt = $mysqli->prepare("INSERT INTO MagazzinoProdotti (magazzinoID, prodottoID, quantita) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $magazzinoID, $pID);
            $stmt->execute();
        }
    }

    if (isset($_POST['incrementa']) || isset($_POST['decrementa']) || isset($_POST['elimina'])) {
        $prodottoID = intval($_POST['prodottoID']);
        $magazzinoID = intval($_POST['magazzinoID']);

        if (isset($_POST['incrementa'])) {
            $stmt = $mysqli->prepare("UPDATE MagazzinoProdotti SET quantita = quantita + 1 WHERE magazzinoID = ? AND prodottoID = ?");
            $stmt->bind_param("ii", $magazzinoID, $prodottoID);
            $stmt->execute();
        } elseif (isset($_POST['decrementa'])) {
            $stmt = $mysqli->prepare("UPDATE MagazzinoProdotti SET quantita = GREATEST(0, quantita - 1) WHERE magazzinoID = ? AND prodottoID = ?");
            $stmt->bind_param("ii", $magazzinoID, $prodottoID);
            $stmt->execute();
        } elseif (isset($_POST['elimina'])) {
            $stmt = $mysqli->prepare("DELETE FROM MagazzinoProdotti WHERE magazzinoID = ? AND prodottoID = ?");
            $stmt->bind_param("ii", $magazzinoID, $prodottoID);
            $stmt->execute();
        }
    }
}

// Recupera magazzini
$magazzini = [];
$res = $mysqli->query("SELECT * FROM Magazzino");
while ($row = $res->fetch_assoc()) {
    $magazzini[] = $row;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Prodotti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <h2>Gestione Prodotti per Magazzino</h2>
        <a href="index.php" class="logout-btn">Logout</a>
    </div>

    <div class="admin-content">
        <div class="add-product-form">
            <h3>Aggiungi Nuovo Prodotto</h3>
            <form method="post">
                <label for="nome">Nome Prodotto</label>
                <input type="text" id="nome" name="nome" required>
                <label for="prezzo">Prezzo (€)</label>
                <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" required>
                <label for="magazzinoID">Magazzino</label>
                <select name="magazzinoID" id="magazzinoID" required>
                    <?php foreach ($magazzini as $m): ?>
                        <option value="<?= $m['mID'] ?>"><?= htmlspecialchars($m['posizione']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="aggiungi">Aggiungi</button>
            </form>
        </div>

        <div class="products-list">
            <h3>Prodotti nei Magazzini</h3>
            <table>
                <thead>
                <tr>
                    <th>Nome Prodotto</th>
                    <th>Magazzino</th>
                    <th>Quantità</th>
                    <th>Azioni</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "
                    SELECT p.pID, p.nome, m.mID, m.posizione, mp.quantita
                    FROM MagazzinoProdotti mp
                    JOIN prodotti p ON mp.prodottoID = p.pID
                    JOIN Magazzino m ON mp.magazzinoID = m.mID
                    ORDER BY p.nome, m.posizione
                ";
                $result = $mysqli->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['posizione']) . "</td>";
                        echo "<td>" . intval($row['quantita']) . "</td>";
                        echo "<td>";
                        echo "<form method='post' style='display:inline'>";
                        echo "<input type='hidden' name='prodottoID' value='{$row['pID']}'>";
                        echo "<input type='hidden' name='magazzinoID' value='{$row['mID']}'>";
                        echo "<button type='submit' name='incrementa'>+</button>";
                        echo "<button type='submit' name='decrementa'>-</button>";
                        echo "<button type='submit' name='elimina'>Elimina</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nessun prodotto presente nei magazzini</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
