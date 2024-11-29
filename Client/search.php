<?php
include ("../function/search_nav.php");
include ("../function/user_icon.php");
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
    <link rel="stylesheet" href="../css/style_search.css">
    <link rel="stylesheet" href="../css/header_user.css">
    <link rel="stylesheet" href="../css/footer_user.css">
    <title>PixelHub | Search</title>
</head>

<body>
     <header>
            <?php include ("../function/header_user.php"); ?>
        </header>
    <div class="container-fluid">
       

        <main class="container">
            <section class="search-results">
                <?php if (!empty($photos_results)): ?>
                    <div class="result-section photos">
                        <h2>Photos Results</h2>
                        <ul>
                            <?php foreach ($photos_results as $photo): ?>
                                <li><?= htmlspecialchars($photo['Description']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($articles_results)): ?>
                    <div class="result-section articles">
                        <h2>Articles Results</h2>
                        <ul>
                            <?php foreach ($articles_results as $article): ?>
                                                <div class="post-card">
                                                    <div class="info-user">
                                                        <p>
                                                            <a href="profile.php">
                                                                <img src="<?= isset($user_photo) ? htmlspecialchars($user_photo) : ''; ?>"
                                                                    alt="Profile Image"
                                                                    style="width: 50px;height: 50px;border-radius: 50%;object-fit: cover;border: 2px solid #ccc;margin-top: 10px;">
                                                            </a>
                                                            <?= isset($article['Prenom']) && isset($article['Nom']) ? htmlspecialchars($article['Prenom']) . " " . htmlspecialchars($article['Nom']) : 'Unknown' ?>
                                                            <span
                                                                style="position: relative;top: 14px;left: -98px;font-size: 9px;color: lightgray;">
                                                                <?= isset($article['DatePublication']) ? htmlspecialchars($article['DatePublication']) : '' ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div class="content">
                                                        <h4><?= isset($article['Contenu']) ? htmlspecialchars($article['Contenu']) : '' ?>
                                                        </h4>
                                                        <?php if (!empty($article['Image'])): ?>
                                                            <img src="<?= htmlspecialchars($article['Image']) ?>"
                                                                alt="Article Image">
                                                        <?php endif; ?>
                                                    </div>
                                                    <form method="post" action="">
                                                        <input type="hidden" name="article_id"
                                                            value="<?= isset($article['IdArticle']) ? htmlspecialchars($article['IdArticle']) : '' ?>">
                                                        <button type="button" class="btn btn-success edit-btn"
                                                            data-id="<?= isset($article['IdArticle']) ? htmlspecialchars($article['IdArticle']) : '' ?>">Edit</button>
                                                        <button type="button" class="btn btn-danger delete-btn"
                                                            data-id="<?= isset($article['IdArticle']) ? htmlspecialchars($article['IdArticle']) : '' ?>">Delete</button>
                                                    </form>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>



                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($faq_results)): ?>
                    <div class="result-section faq">
                        <h2>FAQ Results</h2>
                        <ul>
                            <?php foreach ($faq_results as $faq): ?>
                                <li><?= htmlspecialchars($faq['Question']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (empty($photos_results) && empty($articles_results) && empty($faq_results)): ?>
                    <h2 class="text-center p-4">No results found 🙄 .</h2>
                <?php endif; ?>
            </section>
        </main>
        <div>
            <footer class="footer animate__animated animate__slideInUp">
                    <div class="d-flex justify-content-center">
                        <?php include ("../function/footer_user.php"); ?>
                    </div
            </footer>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../Js/script.js">
    </script>

</body>

</html>