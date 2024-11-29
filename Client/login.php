<?php
session_start();

require '../function/db.php';

if (isset($_SESSION['UserID'])) {
    $user_id = $_SESSION['UserID'];
} else {
    $user_id = '';
}
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $user = $_POST['Username'];
    $user = filter_var($user, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['Password']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $sql = "SELECT UserID, Username, FirstName, LastName, Email, DateOfBirth, ProfilPhoto, Password 
            FROM Users 
            WHERE Email = :identifier OR Username = :identifier";
    $select_user = $conn->prepare($sql);
    $select_user->execute(['identifier' => $email || $user]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($pass, $row['Password'])) {
        $_SESSION['UserID'] = $row['UserID'];
        $_SESSION['userName'] = $row['Username'];
        $_SESSION['nameUser'] = $row['FirstName'] . ' ' . $row['LastName'];
        $_SESSION['email'] = $row['Email'];
        $_SESSION['userPhoto'] = $row['ProfilPhoto'];
        $_SESSION['birth'] = $row['DateOfBirth'];
    } else {
        $message[] = 'Incorrect username or password!';
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_login.css">
    <title>PixelHub | Login</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <header>
                <div class="logo animate__flash">
                    <a href="home.php">
                        <h1>Pixel<span>Hub</span></h1>
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href="register.php" class="btn btn-light animate__pulse" id="btn">Register</a></li>
                    </ul>
                </nav>
            </header>
            <section class="animate__bounceInLeft">
                <div class="container">
                    <div class="form card">
                        <h2>Login</h2>

                        <?php
                        if (isset($_SESSION['error_message'])) {
                            echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                            unset($_SESSION['error_message']);
                        }
                        ?>

                        <form method="post" action="">
                            <div class="form-group">
                                <label for="identifier">Email or Username:</label>
                                <input type="text" class="form-control" id="identifier" name="identifier" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-secondary">Login</button>
                        </form>

                    </div>
                </div>
            </section>
            <footer class="footer animate__animated animate__slideInUp">
                <div class="container text-center">
                    <p>&copy; copyright @ <?= date('Y'); ?> by <span id="logo2">Pixel<span>Hub</span></span> | all
                        rights reserved!</p>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
