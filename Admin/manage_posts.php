<?php
include ("../function/db.php");



// Function to check if the user is an admin
function isAdmin($userId) {
    global $conn;
    $query = $conn->prepare("SELECT * FROM administrateurs WHERE IdUtilisateur = ? AND NiveauAcces >= ?");
    $query->execute([$userId, 1]); // Assuming NiveauAcces 1 or higher is admin
    return $query->fetch() ? true : false;
}

// Handle photo post deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['photo_id'];
    $delete_query = $conn->prepare("DELETE FROM photos WHERE IdPhoto = ?");
    $delete_query->execute([$id]);
    echo "<p>Photo deleted successfully.</p>";
}

// Fetch all photo posts from the database
$fetch_photos = $conn->prepare("SELECT p.*, u.Nom, u.Prenom, u.ProfilPhoto FROM photos p JOIN utilisateurs u ON p.IdUtilisateur = u.IdUtilisateur ORDER BY p.DatePublication DESC");
$fetch_photos->execute();
$photos = $fetch_photos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            display: flex;
            background-color: #121212; /* Dark background */
            color: #e0e0e0; /* Light text */
        }
        .container {
            max-width: 100%;
            margin-left: 200px; /* Space for the sidebar */
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
        .img-thumbnail {
            width: 100px;
            height: auto;
        }
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 200px;
            background-color: #343a40; /* Darker grey sidebar */
            padding: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.3);
        }
        .sidebar a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #ccc; /* Light grey text */
            font-weight: 500;
        }
        .sidebar a:hover {
            background-color: #495057; /* Even darker on hover */
            color: #ffffff;
        }
        .card {
            background-color: #222831; /* Dark cards */
            border: 1px solid #393e46; /* Slightly lighter border for visibility */
        }
        .card-body {
            color: #ececec; /* Light grey text for readability */
        }
        .card-title {
            color: #ffffff; /* White color for titles */
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
        <h1 style="display:flex; justify-content:center;margin-bottom:10px;">Photo Posts</h1>
        <hr>
        <div class="photo-posts">
            <?php foreach ($photos as $photo): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text"><?= htmlspecialchars($photo['Description']) ?></p>
                        <img src="<?= htmlspecialchars($photo['CheminImage']) ?>" class="img-thumbnail" alt="Photo">
                        <div class="user-info mt-2">
                            <img src="<?= htmlspecialchars($photo['ProfilPhoto']) ?>" class="profile-img">
                            <small class="text-muted">Posted by <?= htmlspecialchars($photo['Prenom'] . ' ' . $photo['Nom']) ?> on <?= $photo['DatePublication'] ?></small>
                        </div>
                        <form method="post" action="">
                            <input type="hidden" name="photo_id" value="<?= $photo['IdPhoto'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($photos)): ?>
                <p class="text-center">No photo posts available.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
