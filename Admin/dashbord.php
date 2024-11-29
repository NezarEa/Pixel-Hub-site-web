<?php
session_start();

if (!isset($_SESSION['adminId'])) {
    header("Location: login-admin.php");
    exit();
}

$total_users = 100;
$total_photo_posts = 200;
$total_blog_posts = 50;
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
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
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
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_posts.php">Manage Photo Posts</a>
        <a href="manage_blogs.php">Manage Blogs</a>
        <a href="faq.php">FAQ</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">User Information</h5>
                <p>Total number of registered users: <?php echo $total_users; ?></p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Post Information</h5>
                <p>Total number of photo posts: <?php echo $total_photo_posts; ?></p>
                <p>Total number of blog posts: <?php echo $total_blog_posts; ?></p>
            </div>
        </div>
    </div>
</body>

</html>