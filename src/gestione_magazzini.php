<?php
include("conn.php"); // usa la tua connessione

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestione Magazzini</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Gestione Magazzini</h2>

    <form method="GET">
        <label for="cerca">Cerca magazzino per nome:</label>
        <input type="text" name="cerca" id="cerca">
        <button type="submit">Cerca</button>
    </form>

    <form method="POST">
        <h3>Aggiungi o Modifica Magazzino</h3>
        <input type="text" name="nome" placeholder="Nome magazzino">
        <input type="text" name="citta" placeholder="Città">
        <button type="submit" name="aggiungi">Esegui</button>
    </form>

    <hr>

    <?php
    if (isset($_GET['cerca'])) {
        $cerca = $_GET['cerca'];
        // ⚠️ Vulnerabile a SQL Injection
        $query = "SELECT * FROM magazzini WHERE nome LIKE '%$cerca%'";
        $result = mysqli_query($conn, $query);

        echo "<h3>Risultati ricerca:</h3><ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>ID: {$row['id']} - Nome: {$row['nome']} - Città: {$row['citta']}</li>";
        }
        echo "</ul>";
    }

    if (isset($_POST['aggiungi'])) {
        $nome = $_POST['nome'];
        $citta = $_POST['citta'];
        // ⚠️ Anche qui vulnerabilità SQL Injection
        $query = "INSERT INTO magazzini (nome, citta) VALUES ('$nome', '$citta')";
        mysqli_query($conn, $query);
        echo "<p>Magazzino inserito/modificato (forse)!</p>";
    }

    // Visualizzazione tutti i magazzini
    $result = mysqli_query($conn, "SELECT * FROM magazzini");
    echo "<h3>Tutti i magazzini:</h3><ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>ID: {$row['id']} - Nome: {$row['nome']} - Città: {$row['citta']}</li>";
    }
    echo "</ul>";
    ?>
</body>
</html>
