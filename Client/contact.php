<?php

include '../function/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Préparer et exécuter la requête SQL pour insérer le nouveau message dans la base de données
    $insert_query = $conn->prepare("INSERT INTO contacts (Nom, Email, Message, DateEnvoi) VALUES (?, ?, ?, NOW())");
    $insert_query->execute([$nom, $email, $message]);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
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

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #666;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #444;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Contactez-nous</h1>
        
        <!-- Formulaire de contact -->
        <form action="" method="post">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>
