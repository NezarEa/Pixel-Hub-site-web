<?php
include '../function/db.php';
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch contact messages from the database
$fetch_messages = $conn->prepare("SELECT * FROM contacts ORDER BY DateEnvoi DESC");
$fetch_messages->execute();
$messages = $fetch_messages->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <style>
        body {
    background-color: #000;
    color: #fff;
    font-family: Arial, sans-serif;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    color: #fff;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    border: 1px solid #fff;
}

thead {
    background-color: #222;
}

thead th {
    text-align: left;
}

tbody tr:nth-child(even) {
    background-color: #333;
}

tbody tr:hover {
    background-color: #444;
}

    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Contact Messages</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date Envoi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message) : ?>
                    <tr>
                        <td><?= $message['Nom'] ?></td>
                        <td><?= $message['Email'] ?></td>
                        <td><?= $message['Message'] ?></td>
                        <td><?= $message['DateEnvoi'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
