<?php
session_start();


if (!isset($_SESSION['adminId'])) {
    
    header("Location: login-admin.php");
    exit();
}

include("../function/db.php");

function isAdmin($userId) {
    global $conn;
    $query = $conn->prepare("SELECT * FROM administrateurs WHERE IdUtilisateur = ? AND NiveauAcces >= ?");
    $query->execute([$userId, 1]);
    return $query->fetch() ? true : false;
}


$fetch_users = $conn->prepare("SELECT * FROM utilisateurs ORDER BY Nom, Prenom ASC");
$fetch_users->execute();
$users = $fetch_users->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $userId = $_POST['user_id'];
    $delete_query = $conn->prepare("DELETE FROM utilisateurs WHERE IdUtilisateur = ?");
    $delete_query->execute([$userId]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            display: flex;
            background-color: #121212;
            color: #e0e0e0;
        }
        .container {
            max-width: 100%;
            margin-left: 200px;
            padding: 20px;
        }
        .btn {
            margin-top: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
        }
        .btn-primary:hover, .btn-danger:hover {
            opacity: 0.85;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 200px;
            background-color: #343a40;
            padding: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .sidebar a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #ccc;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #ffffff;
        }
        .table-dark {
            background-color: #222831;
            border: 1px solid #393e46;
        }
        .table-dark th, .table-dark td {
            border-color: #414a4c;
        }
    </style>
</head>
<body>
<div class="sidebar">
        <h4>Admin Menu</h4>
        <a href="dashbord.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_posts.php">Manage Photo Posts</a>
        <a href="manage_blogs.php">Manage Blogs</a>
        <a href="faq.php">FAQ</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h1>Manage Users</h1>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($user['IdUtilisateur']) ?></th>
                    <td><?= htmlspecialchars($user['Nom']) . " " . htmlspecialchars($user['Prenom']) ?></td>
                    <td><?= htmlspecialchars($user['Email']) ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm">Edit</button>
                        <form method="post" action="" style="display: inline-block;">
                            <input type="hidden" name="user_id" value="<?= $user['IdUtilisateur'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
