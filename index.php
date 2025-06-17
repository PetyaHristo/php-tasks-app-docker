<?php
$host = 'db';
$user = 'root';
$pass = 'root';
$db   = 'task_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Грешка при свързване: " . $conn->connect_error);
}

// Добавяне на задача
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $conn->query("INSERT INTO tasks (title) VALUES ('$title')");
    header("Location: index.php");
    exit;
}

// Изтриване на задача
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id = $id");
    header("Location: index.php");
    exit;
}

// Маркиране като завършена
if (isset($_GET['complete'])) {
    $id = (int) $_GET['complete'];
    $conn->query("UPDATE tasks SET completed = NOT completed WHERE id = $id");
    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Задачи</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
            max-width: 600px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            margin-bottom: 20px;
            gap: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: white;
            margin-bottom: 10px;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .completed {
            text-decoration: line-through;
            color: #888;
        }
        .btn-group {
            display: flex;
            gap: 6px;
        }
        .delete-btn, .done-btn {
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #a71d2a;
        }
        .done-btn {
            background-color: #28a745;
        }
        .done-btn:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <h1>📋 Моите задачи</h1>

    <form method="POST">
        <input type="text" name="title" placeholder="Въведи нова задача..." required>
        <button type="submit">Добави</button>
    </form>

    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <span class="<?= $row['completed'] ? 'completed' : '' ?>">
                    <?= htmlspecialchars($row['title']) ?>
                </span>
                <div class="btn-group">
                    <form method="GET" style="margin:0;">
                        <input type="hidden" name="complete" value="<?= $row['id'] ?>">
                        <button class="done-btn" type="submit">✅</button>
                    </form>
                    <form method="GET" style="margin:0;">
                        <input type="hidden" name="delete" value="<?= $row['id'] ?>">
                        <button class="delete-btn" type="submit">🗑️</button>
                    </form>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
