<?php
session_start();
include("config.php");

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 

if (!isset($_GET['article_id'])) {
    die("No article ID provided.");
}

$article_id = $_GET['article_id'];

$query = "SELECT comment_id, content, comment_at, username 
          FROM comments 
          WHERE article_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error); 
}

$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 2.5em;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            padding: 8px 15px;
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        td a:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <h1>Comments for Article ID: <?= htmlspecialchars($article_id) ?></h1>
    <table>
        <tr>
            <th>User</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($comment = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($comment['username']) ?></td>
                <td><?= htmlspecialchars($comment['content']) ?></td>
                <td><?= htmlspecialchars($comment['comment_at']) ?></td>
                <td>
                    <a href="deleteComment.php?comment_id=<?= $comment['comment_id'] ?>&article_id=<?= $article_id ?>" 
                       onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>
