<?php
/*
 Simple Volunteer Manager CRUD page
 Requirements:
 - PHP with PDO
 - MySQL database with a table `Volunteer_Managers`:
   CREATE TABLE `Volunteer_Managers` (
     `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
     `name` VARCHAR(255) NOT NULL
   );
 - Update the DB credentials below
*/

// Database configuration
$host = 'localhost';
$db   = 'association_database_v3';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . htmlspecialchars($e->getMessage());
    exit;
}

// Handle actions: add, edit, delete
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'add' && !empty($_POST['name'])) {
        $stmt = $pdo->prepare('INSERT INTO Volunteer_Managers (name) VALUES (:name)');
        $stmt->execute(['name' => $_POST['name']]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'edit' && !empty($_POST['name']) && !empty($_POST['id'])) {
        $stmt = $pdo->prepare('UPDATE Volunteer_Managers SET name = :name WHERE id = :id');
        $stmt->execute(['name' => $_POST['name'], 'id' => $_POST['id']]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'delete' && !empty($_GET['id'])) {
        $stmt = $pdo->prepare('DELETE FROM Volunteer_Managers WHERE id = :id');
        $stmt->execute(['id' => $_GET['id']]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all volunteer managers
$stmt = $pdo->query('SELECT id, name FROM Volunteer_Managers ORDER BY id ASC');
$managers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Managers</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        form { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Volunteer Managers</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($managers as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['id']) ?></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td>
                    <a href="?action=edit_form&id=<?= $m['id'] ?>">Edit</a> |
                    <a href="?action=delete&id=<?= $m['id'] ?>" onclick="return confirm('Delete this manager?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (isset($_GET['action']) && $_GET['action'] === 'edit_form' && !empty($_GET['id'])):
        // Fetch single record for editing
        $stmt = $pdo->prepare('SELECT id, name FROM Volunteer_Managers WHERE id = :id');
        $stmt->execute(['id' => $_GET['id']]);
        $edit = $stmt->fetch();
        if ($edit): ?>
    <h2>Edit Manager</h2>
    <form method="post" action="?action=edit">
        <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id']) ?>">
        <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($edit['name']) ?>" required></label>
        <button type="submit">Update</button>
        <a href="<?= $_SERVER['PHP_SELF'] ?>">Cancel</a>
    </form>
    <?php endif; elseif (!isset($_GET['action']) || $_GET['action'] !== 'edit_form'): ?>
    <h2>Add New Manager</h2>
    <form method="post" action="?action=add">
        <label>Name: <input type="text" name="name" required></label>
        <button type="submit">Add</button>
    </form>
    <?php endif; ?>

</body>
</html>
