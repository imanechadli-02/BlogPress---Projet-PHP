<?php
session_start();
include 'config.php';

if (isset($_GET['article_id'])) {
    echo 'tets';
    $article_id = intval($_GET['article_id']); // Sanitize the ID
    echo "Debug: Article ID is $article_id<br>";


    // Delete the article
    $query = "DELETE FROM articles WHERE article_id = $article_id";
    if (mysqli_query($conn, $query)) {
        echo "Article deleted successfully.";
    } else {
        echo "Error deleting article: " . mysqli_error($conn);
    }
} else {
    echo "No article ID provided.";
}
?>
