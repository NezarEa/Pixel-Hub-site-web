<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_login.css">
    <title>PixelHub | Login-admin</title>
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
            </header>
            <section class="animate__bounceInLeft">
                <div class="container">
                    <div class="form card">
                        <h2>Login</h2>
                        <?php
                        session_start();

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            require '../function/db.php';
                            $username = htmlspecialchars($_POST['username']);
                            $password = $_POST['password'];

                            $sql = "SELECT * FROM admins WHERE Username = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$username]);
                            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($admin && password_verify($password, $admin['Password'])) {
                                $_SESSION['adminId'] = $admin['AdminID'];
                                $_SESSION['adminName'] = $admin['Username'];
                                header("Location: dashboard.php");
                                exit();
                            }
                        }
                        ?>


                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
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
            <footer class="footer animate_animated animate_slideInUp">
                <div class="container text-center">
                    <p>&copy; copyright @ <?= date('Y'); ?> by <span id="logo2">Pixel<span>Hub</span></span> | all
                        rights reserved!</p>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>