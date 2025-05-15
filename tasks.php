<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Agregar tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $title, $description]);
    header("Location: tasks.php");
    exit;
}

// Marcar como completada
if (isset($_GET['complete'])) {
    $stmt = $pdo->prepare("UPDATE tasks SET completed = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['complete'], $userId]);
    header("Location: tasks.php");
    exit;
}

// Eliminar tarea
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['delete'], $userId]);
    header("Location: tasks.php");
    exit;
}

// Filtro
$filter = $_GET['filter'] ?? 'all';
$query = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$userId];

if ($filter === 'completed') {
    $query .= " AND completed = 1";
} elseif ($filter === 'pending') {
    $query .= " AND completed = 0";
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>

<h2>Mis Tareas</h2>

<form method="post">
    <input type="text" name="title" required placeholder="Título">
    <textarea name="description" placeholder="Descripción"></textarea>
    <button type="submit">Agregar Tarea</button>
</form>

<p>
    <a href="?filter=all">Todas</a> |
    <a href="?filter=completed">Completadas</a> |
    <a href="?filter=pending">Pendientes</a>
</p>

<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong><?= htmlspecialchars($task['title']) ?></strong>
        <?php if ($task['completed']): ?>
            ✅
        <?php else: ?>
            <a href="?complete=<?= $task['id'] ?>">[Marcar completada]</a>
        <?php endif; ?>
        <a href="?delete=<?= $task['id'] ?>">[Eliminar]</a>
        <p><?= nl2br(htmlspecialchars($task['description'])) ?></p>
    </li>
<?php endforeach; ?>
</ul>

<p><a href="logout.php">Cerrar sesión</a></p>

