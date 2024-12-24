<?php
include 'config.php';

session_start();

$user_id = $_SESSION['id'];

$result = $conn->query("SELECT * FROM auteur WHERE user_id = $user_id");
if (!$result || $result->num_rows == 0) {
    die("Erreur : Utilisateur introuvable dans la table 'auteur'.");
}

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $article_img = mysqli_real_escape_string($conn, $_POST['article_img']); 

    if (!filter_var($article_img, FILTER_VALIDATE_URL)) {
        die("Erreur : URL de l'image invalide.");
    }

    $query = "
        INSERT INTO articles (user_id, article_img, article_titre, article_categorie, article_content)
        VALUES ($user_id, '$article_img', '$title', '$category', '$content')";
    if ($conn->query($query)) {
        echo "<script>alert('Article ajouté avec succès !'); window.location.href='manageArticles.php';</script>";
        exit;
    } else {
        die("Erreur SQL : " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Article</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 1.8rem;
            color: #4a90e2;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Styles des champs */
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        textarea {
            resize: none;
            height: 120px;
        }

        input:focus,
        textarea:focus {
            border-color: #4a90e2;
            outline: none;
        }

        /* Bouton */
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #357ab8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <h1>Ajouter un Nouvel Article</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Titre" required>
        <input type="text" name="category" placeholder="Catégorie" required>
        <textarea name="content" placeholder="Contenu" required></textarea>
        <input type="text" name="article_img" placeholder="URL de l'image" required>
        <button type="submit" name="submit">Ajouter l'Article</button>
    </form>
</body>

</html>