<?php

declare (strict_types= 1);

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    try {
        $stmt->execute([$username, $password]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Nombre de usuario ya existe";
    }
}
?>


<!-- HTML del formulario -->
<h2>Registro</h2>
<form method="post">
    <input type="text" name="username" required placeholder="Usuario">
    <input type="password" name="password" required placeholder="Contraseña">
    <button type="submit">Registrar</button>
</form>
<?php if (!empty($error)) echo "<p>$error</p>"; ?>
