<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: tasks.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}

?>

<h2>Iniciar Sesión</h2>
<form method="post">
    <input type="text" name="username" required placeholder="Usuario">
    <input type="password" name="password" required placeholder="Contraseña">
    <button type="submit">Entrar</button>
</form>
<?php if (!empty($error)) echo "<p>$error</p>"; ?>
