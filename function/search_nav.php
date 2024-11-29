<?php

include("../function/db.php");

session_start();

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT Nom, Prenom, Email, ProfilPhoto FROM Utilisateurs WHERE IdUtilisateur = :user_id";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_user->execute();
    $user_info = $stmt_user->fetch(PDO::FETCH_ASSOC);
    $user_name = $user_info['Prenom'] . ' ' . $user_info['Nom']; 
    $user_email = $user_info['Email'];
    $user_photo = $user_info['ProfilPhoto'];
}

$search_query = isset($_GET['query']) ? $_GET['query'] : '';

$photos_results = $articles_results = $faq_results = [];

if (!empty($search_query)) {
    // Corrected the column name to 'Description' for Photos and '' for Articles
    $sql_photos = "SELECT * FROM Photos WHERE Description LIKE :search_query";
    $stmt_photos = $conn->prepare($sql_photos);
    $stmt_photos->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $stmt_photos->execute();
    $photos_results = $stmt_photos->fetchAll(PDO::FETCH_ASSOC);

    $sql_articles = "SELECT * FROM Articles WHERE Contenu LIKE :search_query";
    $stmt_articles = $conn->prepare($sql_articles);
    $stmt_articles->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $stmt_articles->execute();
    $articles_results = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);
}
?>
