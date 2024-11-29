<?php
include '../function/db.php';
session_start();


// Fetch FAQs from the database
$fetch_faq = $conn->prepare("SELECT * FROM faq ORDER BY IdFAQ DESC");
$fetch_faq->execute();
$faqs = $fetch_faq->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Insert a new FAQ
        $question = $_POST['question'];
        $reponse = $_POST['reponse'];
        $insert_query = $conn->prepare("INSERT INTO faq (Question, Reponse) VALUES (?, ?)");
        $insert_query->execute([$question, $reponse]);
    } elseif (isset($_POST['delete'])) {
        // Delete an FAQ
        $idFAQ = $_POST['IdFAQ'];
        $delete_query = $conn->prepare("DELETE FROM faq WHERE IdFAQ = ?");
        $delete_query->execute([$idFAQ]);
    } elseif (isset($_POST['update'])) {
        // Update an FAQ
        $idFAQ = $_POST['IdFAQ'];
        $question = $_POST['question'];
        $reponse = $_POST['reponse'];
        $update_query = $conn->prepare("UPDATE faq SET Question = ?, Reponse = ? WHERE IdFAQ = ?");
        $update_query->execute([$question, $reponse, $idFAQ]);
    }

    // Refresh the page to show updates
    header("Location: faq.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background for the whole page */
            color: #ccc; /* Light text color for readability */
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 200px;
            height: 100%;
            background-color: #343a40;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #ccc;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }
        .container {
            flex-grow: 1;
            padding: 20px;
            margin-left: 200px; /* Provide space for the sidebar */
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            border-bottom: 1px solid #555;
            padding: 10px 0;
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
        <h1>FAQ</h1>
        <form action="faq.php" method="post">
            <input type="hidden" name="add" value="1">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required>
            <label for="reponse">Réponse:</label>
            <textarea id="reponse" name="reponse" rows="4" required></textarea>
            <button type="submit">Add FAQ</button>
        </form>
        <h2>Questions Fréquemment Posées</h2>
        <?php
        if (!empty($faqs)) {
            echo '<ul>';
            foreach ($faqs as $faq) {
                echo '<li>';
                echo '<form action="faq.php" method="post" style="margin-bottom: 10px;">';
                echo '<input type="hidden" name="IdFAQ" value="' . $faq['IdFAQ'] . '">';
                echo '<strong><input type="text" name="question" value="' . htmlspecialchars($faq['Question']) . '" required></strong><br>';
                echo '<textarea name="reponse" rows="4" required>' . htmlspecialchars($faq['Reponse']) . '</textarea><br>';
                echo '<button type="submit" name="update">Update</button>';
                echo '<button type="submit" name="delete" style="background-color: red; margin-left: 10px;">Delete</button>';
                echo '</form>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No FAQs available at the moment.</p>';
        }
        ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
