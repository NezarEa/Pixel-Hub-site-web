<?php
include("../function/db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_photo = isset($_SESSION['userPhoto']) ? $_SESSION['userPhoto'] : '';
$user_name = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if(isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $sql_user = "SELECT Nom, Prenom, Email, ProfilPhoto FROM Utilisateurs WHERE IdUtilisateur = :user_id";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_user->execute();

    
    $user_info = $stmt_user->fetch(PDO::FETCH_ASSOC);
    if ($user_info) { 
        $user_name = $user_info['Prenom'] . ' ' . $user_info['Nom'];
        $user_email = $user_info['Email'];
        $user_photo = $user_info['ProfilPhoto'];
    } else {
        echo "<div class='alert alert-danger'>Unable to retrieve user details.</div>";
    }
}
?>

