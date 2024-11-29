<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../function/db.php';

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $dateOfBirth = htmlspecialchars($_POST['dateOfBirth']);
    $profilePhoto = '';

    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        $allowed = ['jpg' => 'image/jpg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png'];
        $filename = $_FILES['profilePhoto']['name'];
        $filetype = $_FILES['profilePhoto']['type'];
        $filesize = $_FILES['profilePhoto']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (array_key_exists($ext, $allowed)) {
            $profilePhoto = "upload/" . basename($filename);
            move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $profilePhoto);
        }
    }

    $sql = "INSERT INTO Users (Username, Password, Email, FirstName, LastName, DateOfBirth, ProfilPhoto) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $password, $email, $firstName, $lastName, $dateOfBirth, $profilePhoto]);

    if ($stmt->rowCount()) {
        echo "<div class='alert alert-success'>Registration successful!</div>";
        header('location:login.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>User could not be registered.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_register.css">
    <title>PixelHub | Register</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <header>
                <div class="logo animate__flash"><a href="#">
                        <h1>Pixel<span>Hub</span></h1>
                    </a></div>
                <nav>
                    <ul>
                        <li><a href="login.php" class="btn btn-secondary animate__pulse" id="btn">Login</a></li>
                    </ul>
                </nav>
            </header>
            <section class="animate__bounceInLeft">
                <div class="container">
                    <div class="form card">
                        <h1 class="text-center mt-5">Register</h1>
                        <form method="post" action="register.php" enctype="multipart/form-data">
                            <div class="mb-3"><label for="username" class="form-label">Username:</label><input type="text"
                                    class="form-control" id="username" name="username" required></div>
                            <div class="mb-3"><label for="password" class="form-label">Password:</label><input
                                    type="password" class="form-control" id="password" name="password" required></div>
                            <div class="mb-3"><label for="email" class="form-label">Email:</label><input type="email"
                                    class="form-control" id="email" name="email" required></div>
                            <div class="mb-3"><label for="firstName" class="form-label">First Name:</label><input
                                    type="text" class="form-control" id="firstName" name="firstName" required></div>
                            <div class="mb-3"><label for="lastName" class="form-label">Last Name:</label><input
                                    type="text" class="form-control" id="lastName" name="lastName" required></div>
                            <div class="mb-3"><label for="dateOfBirth" class="form-label">Date of Birth:</label><input
                                    type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" required></div>
                            <div class="mb-3"><label for="profilePhoto" class="form-label">Profile Photo:</label><input
                                    type="file" class="form-control" id="profilePhoto" accept="image/jpg, image/jpeg, image/png, image/webp" name="profilePhoto"></div>
                            <button type="submit" class="btn btn-light text-center">Register</button>
                        </form>
                    </div>
                </div>
            </section>
            <footer class="footer animate__animated animate__slideInUp">
                <div class="container text-center">
                    <p>
                        &copy; copyright @ <?= date('Y'); ?> by <span id="logo2">Pixel<span>Hub</span></span> | all
                        rights reserved!
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybBud7RduPuemT//+jJXB16zg6i8UQD3lV5uDC3Yc7bz1Eeow"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>

</html>
