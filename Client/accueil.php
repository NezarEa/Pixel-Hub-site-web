<?php
include("../function/user_icon.php");
$userId = $_SESSION['userId'] ?? null;

// Define $edit_photo with default values
$edit_photo = null;

if ($userId) {
    $query = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
    $query->execute([$userId]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $user_photo = isset($user['ProfilPhoto']) ? $user['ProfilPhoto'] : '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit']) && isset($_POST['photo_id'])) {
        // Assuming $_POST['description'] is provided in the form
        $id = $_POST['photo_id'];
        $description = $_POST['description']; // Define $description variable

        $sql = "UPDATE Photos SET Description = :description WHERE PhotoID = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['description' => $description, 'id' => $id]);
        echo "<p>Photo edited successfully.</p>";
    } elseif (isset($_POST['delete']) && isset($_POST['photo_id'])) {
        $id = $_POST['photo_id'];
        $delete_query = $conn->prepare("DELETE FROM Photos WHERE PhotoID = ?");
        $delete_query->execute([$id]);
        echo "<p>Photo deleted successfully.</p>";
    }

    if (isset($_POST['edit']) && isset($_POST['article_id'])) {
        $id = $_POST['article_id'];
        $content = $_POST['content']; // Define $content variable
        $sql = "UPDATE BlogPosts SET Content = :content WHERE PostID = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['content' => $content, 'id' => $id]);
        echo "<p>Article edited successfully.</p>";
    } elseif (isset($_POST['delete']) && isset($_POST['article_id'])) {
        $id = $_POST['article_id'];
        $delete_query = $conn->prepare("DELETE FROM BlogPosts WHERE PostID = ?");
        $delete_query->execute([$id]);
        echo "<p>Article deleted successfully.</p>";
    }

    // Handle adding comments to photos
    if (isset($_POST['photo_comment']) && isset($_POST['photo_id'])) {
        $photo_id = $_POST['photo_id'];
        $comment_text = $_POST['comment_text'];

        // Insert comment into Comments table
        $insert_comment = $conn->prepare("INSERT INTO Comments (PhotoID, UserID, Comment) VALUES (?, ?, ?)");
        $insert_comment->execute([$photo_id, $userId, $comment_text]);
        echo "<p>Comment added successfully.</p>";
    }

    // Handle adding comments to articles
    if (isset($_POST['article_comment']) && isset($_POST['article_id'])) {
        $article_id = $_POST['article_id'];
        $comment_text = $_POST['comment_text'];

        // Insert comment into Comments table
        $insert_comment = $conn->prepare("INSERT INTO Comments (PostID, UserID, Comment) VALUES (?, ?, ?)");
        $insert_comment->execute([$article_id, $userId, $comment_text]);
        echo "<p>Comment added successfully.</p>";
    }
}

if (isset($_POST['publish']) || isset($_POST['draft'])) {
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

    $blog = isset($_POST['blog']) ? 1 : 0;
    $post = isset($_POST['post']) ? 1 : 0;

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'upload_img_post/' . $image;

    if (isset($_POST['blog'])) {
        $insert_article = $conn->prepare("INSERT INTO BlogPosts (AuthorID, Content) VALUES(?,?)");
        $insert_article->execute([$userId, $content]); // Use $userId instead of $user_id
        header("Location: " . $_SERVER['PHP_SELF']);
        exit; // Ensure script execution stops after redirect
    } elseif (isset($_POST['post'])) {
        $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

        $image = '';
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $image = 'upload_img_post/' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }

        // Insert into Photos table
        $insert_photo = $conn->prepare("INSERT INTO Photos (UserID, Description, ImageURL) VALUES(?,?,?)");
        $insert_photo->execute([$userId, $content, $image]); // Use $userId instead of $user_id

        if (!empty($image)) {
            $image = $edit_photo ? $edit_photo['ImageURL'] : '';
        }

        if (empty($message)) {
            if ($edit_photo) {
                $update_query = $conn->prepare("UPDATE Photos SET Description = ?, ImageURL = ? WHERE PhotoID = ?");
                $update_query->execute([$content, $image, $edit_photo['PhotoID']]);
            } else {
                $insert_post = $conn->prepare("INSERT INTO Photos (UserID, Description, ImageURL) VALUES(?,?,?)");
                $insert_post->execute([$userId, $content, $image]);
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit; 
        }
    }
}

$fetch_photos = $conn->prepare("SELECT p.*, u.FirstName, u.LastName, u.ProfilPhoto FROM Photos p JOIN Users u ON p.UserID = u.UserID ORDER BY p.UploadDate DESC");
$fetch_photos->execute();
$photos = $fetch_photos->fetchAll(PDO::FETCH_ASSOC);


$fetch_articles = $conn->prepare("SELECT * FROM BlogPosts ORDER BY PublishDate DESC");
$fetch_articles->execute();
$articles = $fetch_articles->fetchAll(PDO::FETCH_ASSOC);

$fetch_posts = $conn->prepare("SELECT * FROM Photos ORDER BY UploadDate DESC");
$fetch_posts->execute();
$posts = $fetch_posts->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['edit_photo'])) {
    $edit_photo = $_SESSION['edit_photo'];
}
?>
<?php
function fetch_comments_for_photo($photo_id) {
    global $conn;

    $query = $conn->prepare("SELECT c.*, u.FirstName, u.LastName, u.ProfilPhoto FROM Comments c JOIN Users u ON c.UserID = u.UserID WHERE c.PhotoID = ?");
    $query->execute([$photo_id]);


    return $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php
function fetch_comments_for_article($article_id) {
    global $conn; 
    $query = $conn->prepare("SELECT c.*, u.FirstName, u.LastName, u.ProfilPhoto FROM Comments c JOIN Users u ON c.UserID = u.UserID WHERE c.PostID = ?");
    $query->execute([$article_id]);

    return $query->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../css/style_accueil.css">
    <link rel="stylesheet" href="../css/header_user.css">
    <link rel="stylesheet" href="../css/footer_user.css">
    <title>PixelHub | Accueil</title>
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
                        <div class="col-md-3 profile-section">
                            <div class="info text-center">
                                <?php if ($user): ?>
                                    <div class="mb-3">
                                        <label for="profilePhoto" class="form-label text-body-emphasis">Profile
                                            Photo:</label>
                                        <p><img src="<?= htmlspecialchars($user_photo); ?>" alt=""
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
                                    <button id="editPostBtn" class="toggle-button">Add New Post</button>
                                    <div id="postForm" class="modal">
                                        <div class="modal-content">
                                            <span class="close d-flex" style="justify-content: flex-end;">×</span>
                                            <h1 class="heading">Add New Post</h1>
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <p>Post type <span>*</span></p>
                                                <div class="post-type-options">
                                                    <label><input type="checkbox" name="blog" value="1"
                                                            onclick="toggleCheckbox('blog')"> Blog</label>
                                                    <label><input type="checkbox" name="post" value="1"
                                                            onclick="toggleCheckbox('post')" checked> Post</label>
                                                </div>
                                                <input type="hidden" name="photo_id"
                                                    value="<?= isset($edit_photo['IdPhoto']) ? htmlspecialchars($edit_photo['IdPhoto']) : ''; ?>">
                                                <div id="blogContent">
                                                    <p>Post content <span>*</span></p>
                                                    <textarea name="content" class="box" required maxlength="10000"
                                                        placeholder="Write your content..."><?= htmlspecialchars($edit_photo['Description'] ?? '') ?></textarea>
                                                </div>
                                                <div id="photoContent">
                                                    <p id="post_img">Post image</p>
                                                    <input type="file" name="image" class="box" id="imageInput"
                                                        accept="image/jpg, image/jpeg, image/png, image/webp">
                                                </div>
                                                <div class="flex-btn">
                                                    <?php if ($edit_photo): ?>
                                                        <input type="submit" value="Update Post" name="update" class="btn-me">
                                                        <button type="button" class="btn-me ss"
                                                            onclick="window.location.href='<?php echo $_SERVER["PHP_SELF"]; ?>'">Cancel
                                                            Update</button>
                                                    <?php else: ?>
                                                        <input type="submit" value="Publish Post" name="publish"
                                                            class="add-btn">
                                                        <input type="submit" value="Save Draft" name="draft" class="option-btn">
                                                    <?php endif; ?>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if (!empty($message)): ?>
                                        <div class="messages">
                                            <?php foreach ($message as $msg): ?>
                                                <p><?= $msg ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p>User information not available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="container">
                                <div class="row justify-content-between">
                                    <div class="gallery-container">
                                        <div class="photos">
                                            <?php foreach ($photos as $photo): ?>
                                                <div class="post-card">
                                                    <div class="info-user">
                                                        <p>
                                                            <a href="profile.php">
                                                                <img src="<?= isset($user_photo) ? htmlspecialchars($user_photo) : ''; ?>"
                                                                    alt="Profile Image"
                                                                    style="width: 50px;height: 50px;border-radius: 50%;object-fit: cover;border: 2px solid #ccc;margin-top: 10px;">
                                                            </a>
                                                            <?= isset($photo['Prenom']) && isset($photo['Nom']) ? htmlspecialchars($photo['Prenom']) . " " . htmlspecialchars($photo['Nom']) : 'Unknown' ?>
                                                            <span
                                                                style="position: relative;top: 14px;left: -98px;font-size: 9px;color: lightgray;">
                                                                <?= isset($photo['DatePublication']) ? htmlspecialchars($photo['DatePublication']) : '' ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div class="content">
                                                        <h4><?= isset($photo['Description']) ? htmlspecialchars($photo['Description']) : '' ?>
                                                        </h4>
                                                        <?php if (!empty($photo['CheminImage'])): ?>
                                                            <img src="<?= htmlspecialchars($photo['CheminImage']) ?>"
                                                                alt="Post Image">
                                                        <?php endif; ?>
                                                    </div>
                                                    <form method="post" action="">
                                                        <input type="hidden" name="photo_id"
                                                            value="<?= isset($photo['IdPhoto']) ? htmlspecialchars($photo['IdPhoto']) : '' ?>">
                                                        <button type="button" class="btn btn-success edit-btn"
                                                            data-id="<?= isset($photo['IdPhoto']) ? htmlspecialchars($photo['IdPhoto']) : '' ?>">Edit</button>
                                                        <button type="button" class="btn btn-danger delete-btn"
                                                            data-id="<?= isset($photo['IdPhoto']) ? htmlspecialchars($photo['IdPhoto']) : '' ?>">Delete</button>
                                                    </form>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div class="gallery-container">
                                        <div class="photos">
                                            <?php foreach ($articles as $article): ?>
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



                                    <div class="modal fade" id="editModal" tabindex="-1"
                                        aria-labelledby="editModalLabel" aria-hidden="true">
                                        <form action="" method="post" enctype="multipart/form-data">
                                                <p>Post type <span>*</span></p>
                                                <div class="post-type-options">
                                                    <label><input type="checkbox" name="blog" value="1"
                                                            onclick="toggleCheckbox('blog')"> Blog</label>
                                                    <label><input type="checkbox" name="post" value="1"
                                                            onclick="toggleCheckbox('post')" checked> Post</label>
                                                </div>
                                                <input type="hidden" name="photo_id"
                                                    value="<?= isset($edit_photo['IdPhoto']) ? htmlspecialchars($edit_photo['IdPhoto']) : ''; ?>">
                                                <div id="blogContent">
                                                    <p>Post content <span>*</span></p>
                                                    <textarea name="content" class="box" required maxlength="10000"
                                                        placeholder="Write your content..."><?= htmlspecialchars($edit_photo['Description'] ?? '') ?></textarea>
                                                </div>
                                                <div id="photoContent">
                                                    <p id="post_img">Post image</p>
                                                    <input type="file" name="image" class="box" id="imageInput"
                                                        accept="image/jpg, image/jpeg, image/png, image/webp">
                                                </div>
                                                <div class="flex-btn">
                                                    <?php if ($edit_photo): ?>
                                                        <input type="submit" value="Update Post" name="update" class="btn-me">
                                                        <button type="button" class="btn-me ss"
                                                            onclick="window.location.href='<?php echo $_SERVER["PHP_SELF"]; ?>'">Cancel
                                                            Update</button>
                                                    <?php else: ?>
                                                        <input type="submit" value="Publish Post" name="publish"
                                                            class="add-btn">
                                                        <input type="submit" value="Save Draft" name="draft" class="option-btn">
                                                    <?php endif; ?>
                                                </div>
                                            </form>
                                    </div>
                                    <div class="modal fade" id="deleteModal" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Toggle navigation menu
            $('.menu-icon').click(function () {
                $('.nav-links').toggleClass('active');
            });

            // Toggle post form visibility
            $('#editPostBtn').click(function () {
                $('#postForm').toggle();
            });

            // Close post form
            $('.close').click(function () {
                $('#postForm').hide();
            });

            // Edit button click handler
            $(".edit-btn").click(function () {
                var id = $(this).data("id");
                var url = $(this).data("photo-id") ? "edit_photo.php" : "edit_article.php";
                $.get(url, { id: id }, function (data) {
                    $("#editModal .modal-content").html(data);
                    $('#editModal').modal('show');
                });
            });

            // Delete button click handler
            $(".delete-btn").click(function () {
                var id = $(this).data("id");
                var url = $(this).data("photo-id") ? "delete_photo.php" : "delete_article.php";
                $.get(url, { id: id }, function (data) {
                    $("#deleteModal .modal-content").html(data);
                    $('#deleteModal').modal('show');
                });
            });
        });

        // Toggle between blog and post content
        function toggleCheckbox(type) {
            const blogContent = document.getElementById('blogContent');
            const photoContent = document.getElementById('photoContent');
            if (type === 'blog') {
                blogContent.style.display = 'block';
                photoContent.style.display = 'none';
            } else if (type === 'post') {
                blogContent.style.display = 'none';
                photoContent.style.display = 'block';
            }
        }

        // Toggle menu
        function menuToggle() {
            const toggleMenu = document.querySelector('.menu');
            toggleMenu.classList.toggle('active');
        }

        // Toggle checkbox
        function toggleCheckbox(checkbox) {
            if (checkbox === 'blog') {
                document.getElementsByName('post')[0].checked = false;
                document.getElementById('post_img').style.display = "none";
                document.getElementById('imageInput').style.display = 'none';
            } else if (checkbox === 'post') {
                document.getElementById('post_img').style.display = "block";
                document.getElementById('imageInput').style.display = 'block';
                document.getElementsByName('blog')[0].checked = false;
            }
        }

        // Toggle post form visibility
        document.getElementById('toggleForm').addEventListener('click', function () {
            const postForm = document.getElementById('postForm');
            if (postForm.style.display === 'none') {
                postForm.style.display = 'block';
            } else {
                postForm.style.display = 'none';
            }
        });

        $(document).ready(function () {
            // Handle favorite button click
            $(".favorite-btn").click(function () {
                var articleId = $(this).data("article-id");
                var action = $(this).hasClass("favorited") ? "remove" : "add"; // Check if the article is already favorited

                // Send AJAX request to toggle favorite status
                $.ajax({
                    url: "toggle_favorite.php",
                    method: "POST",
                    data: { article_id: articleId, action: action },
                    success: function (response) {
                        if (action == "add") {
                            // Add a class to indicate the article is favorited
                            $(".favorite-btn[data-article-id='" + articleId + "']").addClass("favorited");
                        } else {
                            // Remove the class to indicate the article is not favorited
                            $(".favorite-btn[data-article-id='" + articleId + "']").removeClass("favorited");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });


    </script>
</body>

</html>