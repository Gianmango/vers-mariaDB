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

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['loggato'] = true;
    $_SESSION['username'] = $row['username'];
    $_SESSION['ruolo'] = ($row['ruolo'] === 'admin') ? 'admin' : 'user';

    // Redireziona a home.php dopo il login
    header("Location: home.php");
    exit();
} else {
    echo "<!DOCTYPE html>
    <html lang='it'>
    <head>
        <meta charset='UTF-8'>
        <title>Login Fallito</title>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <div class='result-container'>
            <h2>ACCESSO NEGATO</h2>
            <p>Username o password non validi.</p>
            <a href='index.php'>Torna al login</a>
        </div>
    </body>
    </html>";
}

$conn->close();
?>
