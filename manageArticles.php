<?php
session_start();
include("config.php");

$author_id = $_SESSION['id'];
// echo "Debugging: user_id is $author_id<br>"; 

$query = "SELECT * FROM articles WHERE user_id = $author_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header Styling */
        h1 {
            text-align: center;
            margin-top: 50px;
            font-size: 2.5em;
            color: #333;
        }

        /* Link Styling */
        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #45a049;
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
        }

        th,
        td {
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

        /* Actions Links Styling */
        td a {
            margin-right: 10px;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 0.9em;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        td a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        td a.delete {
            background-color: #dc3545;
        }

        td a.delete:hover {
            background-color: #c82333;
        }



        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                width: 95%;
            }

            h1 {
                font-size: 2em;
            }

            .add-article-btn {
                padding: 12px 20px;
                font-size: 1em;
            }

            td a {
                padding: 6px 12px;
                font-size: 0.8em;
            }
        }
    </style>
</head>

<body>
    <h1>Manage Your Articles</h1>
    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Views</th>
            <th>Likes</th>
            <th>Actions</th>
        </tr>
        <?php while ($article = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $article['article_titre'] ?></td>
                <td><?= $article['article_categorie'] ?></td>
                <td><?= $article['article_views'] ?></td>
                <td><?= $article['article_likes'] ?></td>
                <td>
                    <a href="editArticle.php?article_id=<?= $article['article_id'] ?>">Edit</a>

                    <a href="deleteArticle.php?article_id=<?= $article['article_id'] ?>"
                        onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                    <a href="manageComments.php?article_id=<?= $article['article_id'] ?>">Comments</a>

                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>