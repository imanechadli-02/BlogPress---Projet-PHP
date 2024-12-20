<?php
include "config.php";
session_start();
// Check if article ID is provided
if (isset($_GET['id'])) {
    $article_id = (int) $_GET['id'];

    // Increment the article views
    $conn->query("UPDATE articles SET article_views = article_views + 1 WHERE article_id = $article_id");

    // Fetch the article details including updated views
    $query = "SELECT * FROM articles WHERE article_id = $article_id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $article = $result->fetch_assoc();
        $article_views = $article['article_views']; // Get the updated views
        echo "Debugging: article_views is $article_views<br>";
    } else {
        die("Article not found.");
    }
} else {
    die("No article ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['article_titre']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <header class="bg-white shadow-md py-4">
        <h1 class="text-center text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($article['article_titre']); ?></h1>
    </header>

    <main class="px-6 py-10">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <img src="uploads/<?php echo htmlspecialchars($article['article_img']); ?>" alt="Article Image" class="w-full h-64 object-cover">
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Published on: <?php echo date("d M Y", strtotime($article['article_at'])); ?> | Views: <?php echo $article['article_views']; ?></p>
                <div class="text-gray-800 text-lg">
                    <?php echo nl2br(htmlspecialchars($article['article_content'])); ?>
                </div>
            </div>
        </div>
    </main>

</body>

</html>