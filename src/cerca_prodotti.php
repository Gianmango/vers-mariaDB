<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Ricerca Prodotti</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="main-content">
    <div class="form-container">
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i>Torna al Login</a>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="prodotto">Cerca prodotto:</label>
                <input type="text" id="prodotto" name="prodotto" required placeholder="Inserisci nome prodotto">
            </div>
            <button type="submit" class="login-btn">Cerca</button>
        </form>

        <div class="result-container">
            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["prodotto"])) {
                $prodotto = $_POST["prodotto"]; // Vulnerabile di proposito

                // Recupero prodotto
                $sql = "SELECT pID, nome, quantita FROM prodotti WHERE nome LIKE '%$prodotto%'";
                $res = $conn->query($sql);

                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $pID = $row['pID'];
                        echo "<div class='success-message'>";
                        echo "<strong>Prodotto:</strong> " . $row['nome'] . "<br>";
                        echo "<strong>Totale prodotti disponibili:</strong> " . $row['quantita'] . "<br>";
                        echo "<strong>Presenti nei seguenti centri:</strong><br>";

                        $sql2 = "SELECT m.posizione, mp.quantita 
                                 FROM MagazzinoProdotti mp 
                                 JOIN Magazzino m ON mp.magazzinoID = m.mID 
                                 WHERE mp.prodottoID = $pID";
                        $res2 = $conn->query($sql2);

                        if ($res2 && $res2->num_rows > 0) {
                            echo "<ul>";
                            while ($mag = $res2->fetch_assoc()) {
                                echo "<li><strong>{$mag['posizione']}:</strong> {$mag['quantita']}</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "Nessun magazzino trovato.";
                        }

                        echo "</div><hr>";
                    }
                } else {
                    echo "<div class='error-message'>Prodotto non trovato.</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>
