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