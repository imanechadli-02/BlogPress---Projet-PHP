<?php
include 'config.php';
session_start();

// Check if article_id is provided in the URL
if (isset($_GET['article_id'])) {
    $article_id = $_GET['article_id'];

    // Fetch the article data from the database
    $query = "SELECT * FROM articles WHERE article_id = $article_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    } else {
        die("Article not found.");
    }
} else {
    die("No article ID provided.");
}

// Handle the form submission to update the article
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Check if a new image is uploaded
    $article_img = $article['article_img'];  // Default to the current image if no new image is uploaded

    if (!empty($_FILES["article_img"]["name"])) {
        // If a new image is uploaded, handle the file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["article_img"]["name"]);
        if (move_uploaded_file($_FILES["article_img"]["tmp_name"], $target_file)) {
            $article_img = basename($_FILES["article_img"]["name"]);
        } else {
            die("Error uploading the image.");
        }
    }

    // Update the article in the database
    $update_query = "
        UPDATE articles 
        SET article_titre = '$title', article_categorie = '$category', article_content = '$content', article_img = '$article_img'
        WHERE article_id = $article_id";

    if ($conn->query($update_query)) {
        echo "<script>alert('Article updated successfully!'); window.location.href='manageArticles.php';</script>";
        exit;
    } else {
        die("Error updating article: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 2em;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 1em;
            color: #555;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        input[type="file"] {
            border: none;
        }

        img {
            border-radius: 5px;
            max-width: 100%;
            margin-top: 10px;
        }

        button[type="submit"] {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-group input[type="text"] {
            width: 48%;
        }

        .form-group textarea {
            width: 100%;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9em;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .form-group input[type="text"] {
                width: 100%;
            }
        }

    </style>
</head>

<body>
    <h1>Edit Article</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="title" value="<?= $article['article_titre'] ?>" required><br>
        <input type="text" name="category" value="<?= $article['article_categorie'] ?>" required><br>
        <textarea name="content" required><?= $article['article_content'] ?></textarea><br>

        <label>Current Image:</label>
        <img src="uploads/<?= $article['article_img'] ?>" alt="Article Image" width="100"><br>

        <label>Change Image (optional):</label>
        <input type="file" name="article_img" accept="image/*"><br>

        <button type="submit" name="submit">Update Article</button>
    </form>
</body>

</html>