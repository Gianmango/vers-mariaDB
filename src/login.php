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

$username = $_POST['username'];  
$password = $_POST['password'];

$sql = "SELECT * FROM utenti WHERE username='$username' AND password='$password';";

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="result-container">
        
        <?php
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    if ($result->num_rows > 0) {
                        session_start();
                        $_SESSION['loggato'] = true;
                        $row = $result->fetch_assoc();
                        echo "<h2>Benvenuto, " . htmlspecialchars($row['username']) . "!</h2>";
                        echo "<p>Ruolo: " . htmlspecialchars($row['ruolo']) . "</p>";

                        if ($row['ruolo'] == 'amministratore') {
                            echo "<form action='prodotti.php' method='get' class='button-form'>";
                            echo "<button type='submit' class='admin-button'>";
                            echo "<i class='fas fa-boxes'></i> Gestione prodotti";
                            echo "</button>";
                            echo "</form>";
                        }

                        echo "<form action='cerca_prodotti.php' method='get' class='button-form'>";
                        echo "<button type='submit' class='admin-button'>";
                        echo "<i class='fas fa-boxes'></i> Shop";
                        echo "</button>";
                        echo "</form>";
                        
                    } else {
                        echo "<h2>ACCESSO NEGATO</h2>";
                    }
                    $result->free();
                }
            } while ($conn->next_result());
        } else {
            echo '<div class="result-icon error"><i class="fas fa-exclamation-triangle"></i></div>';
            echo '<div class="result-message">Errore nella query: ' . $conn->error . '</div>';
        }
        ?>
        
    </div>


</body>
</html>
<?php
$conn->close();
?>