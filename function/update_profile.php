<?php
include("../function/db.php");
session_start(); 

$user = []; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userId'] ?? null;
    if ($userId) {
        $query = $conn->prepare("SELECT * FROM Utilisateurs WHERE IdUtilisateur = ?");
        $query->execute([$userId]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
    }

    $nom = isset($_POST['Nom']) ? htmlspecialchars($_POST['Nom']) : ($user['Nom'] ?? '');
    $prenom = isset($_POST['Prenom']) ? htmlspecialchars($_POST['Prenom']) : ($user['Prenom'] ?? '');
    $email = isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ($user['Email'] ?? '');
    $password = isset($_POST['password']) ? $_POST['password'] : ($user['MotDePasse'] ?? ''); 
    
    if (isset($_FILES['ProfilPhoto']) && $_FILES['ProfilPhoto']['error'] === UPLOAD_ERR_OK) {
        if (!empty($user['ProfilPhoto']) && file_exists($user['ProfilPhoto'])) {
            unlink($user['ProfilPhoto']);
        }

        $uploadDir = "../Client/upload/";
        $uploadedFile = $_FILES['ProfilPhoto'];
        $fileName = basename($uploadedFile['name']);
        $newFileName = $uploadDir . $fileName;

        if (move_uploaded_file($uploadedFile['tmp_name'], $newFileName)) {
            $profilePhoto = $newFileName;
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    } else {
        $profilePhoto = $user['ProfilPhoto'] ?? '';
    }

    $sql = "UPDATE Utilisateurs SET Nom = :nom, Prenom = :prenom, Email = :email, MotDePasse = :password, ProfilPhoto = :profilePhoto WHERE IdUtilisateur = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':profilePhoto', $profilePhoto);
    $stmt->bindParam(':userId', $_SESSION['userId']);
    $stmt->execute();

    header("Location: ../Client/profile.php");
    exit();
}
?>
