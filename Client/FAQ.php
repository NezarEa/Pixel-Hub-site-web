<?php

include '../function/db.php';
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Page</title>
    <!-- Integrate CSS styles -->
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #222;
            color: #fff;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h1, h2 {
            color: #fff;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #333;
            color: #fff;
        }

        textarea {
            resize: vertical;
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

        li:last-child {
            border-bottom: none;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>FAQ</h1>
        
        <!-- Form to add a new FAQ -->
        <form action="faq.php" method="post">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required>
            <label for="reponse">Réponse:</label>
            <textarea id="reponse" name="reponse" rows="4" required></textarea>
            <button type="submit">Add FAQ</button>
        </form>

        <!-- Display existing FAQs -->
        <h2>Questions Fréquemment Posées</h2>
        <?php
        // Query to retrieve FAQs
        $fetch_faq = $conn->prepare("SELECT * FROM faq");
        $fetch_faq->execute();
        $faqs = $fetch_faq->fetchAll(PDO::FETCH_ASSOC);

        // Display FAQs as a list
        if (!empty($faqs)) {
            echo '<ul>';
            foreach ($faqs as $faq) {
                echo '<li>';
                echo '<strong>' . $faq['Question'] . '</strong><br>';
                echo $faq['Reponse'];
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No FAQs available at the moment.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $reponse = $_POST['reponse'];

    // Prepare and execute SQL query to insert the new FAQ
    $insert_query = $conn->prepare("INSERT INTO faq (Question, Reponse) VALUES (?, ?)");
    $insert_query->execute([$question, $reponse]);
}
?>
