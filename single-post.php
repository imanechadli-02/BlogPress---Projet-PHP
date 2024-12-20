<?php
include "config.php";
session_start();

// Check if article ID is provided
if (isset($_GET['id'])) {
    $article_id = (int) $_GET['id'];

    // Increment the article views
    $update_query = "UPDATE articles SET article_views = article_views + 1 WHERE article_id = $article_id";
    if (!$conn->query($update_query)) {
        die("Error updating views: " . $conn->error); // Error handling for update query
    }

    // Fetch the article details
    $query = "SELECT * FROM articles WHERE article_id = $article_id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $article = $result->fetch_assoc();
        $article_views = $article['article_views']; // Get the updated views
        $article_likes = $article['article_likes']; // Get the current like count
    } else {
        die("Article not found or invalid article_id.");
    }

    // Handle Like
    if (isset($_POST['like'])) {
        // Update the article_likes column in the articles table
        $article_likes += 1; // Increment the like count
        $like_query = "UPDATE articles SET article_likes = $article_likes WHERE article_id = $article_id";
        if (!$conn->query($like_query)) {
            die("Error updating likes: " . $conn->error); // Error handling for like update query
        }
    }

    // Fetch the comments for this article
    $comment_query = "SELECT * FROM comments WHERE article_id = $article_id ORDER BY comment_at DESC";
    $comments_result = $conn->query($comment_query);
    if (!$comments_result) {
        die("Error fetching comments: " . $conn->error); // Error handling for comment query
    }
} else {
    die("No article ID provided.");
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    // Use session username if available, otherwise fallback to form input
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : $_POST['username'];
    $content = $_POST['content'];

    // Prepare the SQL query for inserting comment
    $stmt = $conn->prepare("INSERT INTO comments (article_id, username, content) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error preparing query: " . $conn->error); // Error handling for prepare
    }

    // Bind parameters to the prepared statement
    if (!$stmt->bind_param("iss", $article_id, $username, $content)) {
        die("Error binding parameters: " . $stmt->error); // Error handling for bind_param
    }

    // Execute the statement
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error); // Error handling for execute
    }

    $stmt->close();

    // Refresh the page to display the new comment
    header("Location: single-post.php?id=$article_id");
    exit();
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

                <!-- Like Button -->
                <form method="POST" action="" class="mt-6">
                    <button type="submit" name="like" class="px-6 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-md">
                        Like (<?php echo $article_likes; ?>)
                    </button>
                </form>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="mt-12">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Comments</h3>

            <?php if ($comments_result->num_rows > 0): ?>
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="border-b-2 pb-4 mb-4">
                        <strong class="text-gray-800"><?php echo htmlspecialchars($comment['username']); ?></strong>
                        <p class="text-sm text-gray-600"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        <span class="text-xs text-gray-400"><?php echo date('d M Y, H:i', strtotime($comment['comment_at'])); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>

            <!-- Comment Form -->
            <form method="POST" action="" class="mt-6">
                <!-- If the user is logged in, don't show the username field -->
                <?php if (!isset($_SESSION['username'])): ?>
                    <div>
                        <label for="username" class="block text-gray-800">Your Username:</label>
                        <input id="username" name="username" type="text" class="w-full p-2 border border-gray-300 rounded-md mt-2" required>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <label for="content" class="block text-gray-800">Your Comment:</label>
                    <textarea id="content" name="content" rows="4" class="w-full p-2 border border-gray-300 rounded-md mt-2" required></textarea>
                </div>
                <button type="submit" class="mt-4 px-6 py-2 text-white bg-purple-500 hover:bg-purple-600 rounded-md">Submit Comment</button>
            </form>
        </div>
    </main>

</body>

</html>