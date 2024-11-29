<?php
include("../function/db.php");
session_start();




function isAdmin($userId) {
    global $conn;
    $query = $conn->prepare("SELECT * FROM administrateurs WHERE IdUtilisateur = ? AND NiveauAcces >= ?");
    $query->execute([$userId, 1]);
    return $query->fetch() ? true : false;
}

// Fetch all blog articles from the database
$fetch_articles = $conn->prepare("SELECT a.*, u.Nom, u.Prenom FROM articles a JOIN utilisateurs u ON a.IdUtilisateur = u.IdUtilisateur ORDER BY a.DatePublication DESC");
$fetch_articles->execute();
$articles = $fetch_articles->fetchAll(PDO::FETCH_ASSOC);

// Handle article post deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $idArticle = $_POST['IdArticle'];
    $delete_query = $conn->prepare("DELETE FROM articles WHERE IdArticle = ?");
    $delete_query->execute([$idArticle]);
    echo "<p>Article deleted successfully.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blog Management</title>
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
        .btn-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
        }
        .btn-danger:hover {
            background-color: #c9302c;
            border-color: #ac2925;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 200px;
            background-color: #343a40;
            padding: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.3);
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
        .card {
            background-color: #222831;
            border: 1px solid #393e46;
        }
        .card-body {
            color: #ececec;
        }
        .card-title {
            color: #ffffff;
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
        <h1 style="display:flex; justify-content:center;margin-bottom:10px;">Blog Articles</h1>
        <hr>
        <div class="article-posts">
            <?php foreach ($articles as $article): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Article by <?= htmlspecialchars($article['Prenom'] . ' ' . $article['Nom']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($article['Contenu'])) ?></p>
                        <small class="text-muted">Published on: <?= htmlspecialchars($article['DatePublication']) ?></small>
                        <form method="post" action="">
                            <input type="hidden" name="IdArticle" value="<?= $article['IdArticle'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($articles)): ?>
                <p class="text-center">No blog articles available.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
