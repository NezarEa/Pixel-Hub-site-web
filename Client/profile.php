<?php
include ("../function/user_icon.php");
$userId = $_SESSION['userId'] ?? null;

if ($userId) {
    $query = $conn->prepare("SELECT * FROM Utilisateurs WHERE IdUtilisateur = ?");
    $query->execute([$userId]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $user_photo = isset($user['ProfilPhoto']) ? $user['ProfilPhoto'] : '';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style_profile.css">
    <link rel="stylesheet" href="../css/header_user.css">
    <link rel="stylesheet" href="../css/footer_user.css">
    <title>PixelHub | Profile</title>
</head>

<body>
    <header>
        <?php include ("../function/header_user.php"); ?>
    </header>
    <div class="container-fluid">

        <main class="container">
            <section>
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-sm-4 profile-section">
                            <div class="info text-center">
                                <h2 class="text-light p-3">User Profile</h2>
                                <?php if ($user): ?>
                                    <div class="mb-3">
                                        <label for="profilePhoto" class="form-label text-body-emphasis">Profile
                                            Photo:</label>
                                        <p><img src="<?php echo htmlspecialchars($user_photo); ?>" alt="Profile Photo"
                                                class="profile-photo"></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nom" class="form-label text-body-emphasis">Nom:</label>
                                        <p class="text-light"><?= htmlspecialchars($user['Nom']) ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label text-body-emphasis">Prenom:</label>
                                        <p class="text-light"><?= htmlspecialchars($user['Prenom']) ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-body-emphasis">Email:</label>
                                        <p class="text-light"><?= htmlspecialchars($user['Email']) ?></p>
                                    </div>
                                <?php else: ?>
                                    <p>User information not available.</p>
                                <?php endif; ?>
                            </div>
                            <button id="editProfileBtn" class="btn-primary">Edit Profile</button>
                            <div id="editProfilePopup" class="popup">
                                <div class="popup-content">
                                    <?php if ($user): ?>
                                        <form action="../function/update_profile.php" method="POST"
                                            enctype="multipart/form-data">
                                            <span id="closePopupBtn" class="close-btn">&times;</span>
                                            <div class="mb-3">
                                                <label for="Nom" class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="Nom" name="Nom"
                                                    value="<?= htmlspecialchars($user['Nom']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="Prenom" class="form-label">Prenom</label>
                                                <input type="text" class="form-control" id="Prenom" name="Prenom"
                                                    value="<?= htmlspecialchars($user['Prenom']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="Email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="Email" name="Email"
                                                    value="<?= htmlspecialchars($user['Email']) ?>" required>
                                            </div>
                                            <div class="mb-3"><label for="password"
                                                    class="form-label">Password:</label><input type="password"
                                                    class="form-control" id="password" name="password"
                                                    value="<?php echo isset($user['MotDePasse']) ? htmlspecialchars($user['MotDePasse']) : ''; ?>"
                                                    required></div>
                                            <div class="mb-3">
                                                <label for="ProfilPhoto" class="form-label">Profil Photo</label>
                                                <input type="file" class="form-control" id="ProfilPhoto"
                                                    accept="image/jpg, image/jpeg, image/png, image/webp"
                                                    value="<?php echo htmlspecialchars($user_photo); ?>" name="ProfilPhoto">
                                                <img src="<?php echo htmlspecialchars($user_photo); ?>" alt="Profile Photo"
                                                    class="profile-photo">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </form>
                                    <?php else: ?>
                                        <p>User information not available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 profile-section">
                            <div class="photo text-center">
                                <h2 class="text-light">Profile Photo</h2>
                                <div class="row mt-5" id="filter-buttons">
                                    <div class="col-12">
                                        <button class="btn mb-2 me-1 active" data-filter="post"><i
                                                class="fa-regular fa-image"></i> Post</button>
                                        <button class="btn mb-2 mx-1" data-filter="favoris"><i
                                                class="fa-solid fa-heart"></i> Favoris</button>
                                    </div>
                                </div>

                                

                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer animate__animated animate__slideInUp">
            <div class="d-flex justify-content-center">
                <?php include ("../function/footer_user.php"); ?>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.menu-icon').click(function () {
                $('.nav-links').toggleClass('active');
            });
        });

        function menuToggle() {
            const toggleMenu = document.querySelector('.menu');
            toggleMenu.classList.toggle('active');
        }
        document.getElementById('editProfileBtn').addEventListener('click', function () {
            document.getElementById('editProfilePopup').classList.toggle('show');
        });
        document.getElementById('closePopupBtn').addEventListener('click', function () {
            document.getElementById('editProfilePopup').classList.remove('show');
        });
        const filterButtons = document.querySelectorAll("#filter-buttons button");
        const filterableCards = document.querySelectorAll("#filterable-cards .card");

        const filterCards = (e) => {
            document.querySelector("#filter-buttons .active").classList.remove("active");
            e.target.classList.add("active");

            filterableCards.forEach(card => {
                if (card.dataset.name === e.target.dataset.filter || e.target.dataset.filter === "post") {
                    return card.classList.replace("hide", "show");
                }
                card.classList.add("hide");
            });
        }

        filterButtons.forEach(button => button.addEventListener("click", filterCards));

    </script>
</body>

</html>