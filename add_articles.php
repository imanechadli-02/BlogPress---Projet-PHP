<?php
include 'config.php';

session_start();

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";


$user_id = $_SESSION['id'];

// echo "Debugging: user_id is $user_id<br>";  // Check if user_id is correct
$query = "SELECT * FROM auteur WHERE user_id = $user_id";
// echo "Debugging: SQL Query: $query<br>";  // Check if query is correct
$result = $conn->query($query);


// Vérification de l'existence de l'utilisateur dans `auteur`
$result = $conn->query("SELECT * FROM auteur WHERE user_id = $user_id");
if (!$result || $result->num_rows == 0) {
    die("Erreur : Utilisateur introuvable dans la table 'auteur'.");
}

// Gestion de l'envoi du formulaire
if (isset($_POST['submit'])) {
    // Protection contre les injections SQL
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Gestion de l'upload de l'image
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["article_img"]["name"]);
    if (!move_uploaded_file($_FILES["article_img"]["tmp_name"], $target_file)) {
        die("Erreur : Impossible de téléverser l'image.");
    }

    $article_img = basename($_FILES["article_img"]["name"]);

    // Insertion dans la table `articles`
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
        /* Styles généraux */
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

        /* Conteneur du formulaire */
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
        <input type="file" name="article_img" accept="image/*" required>
        <button type="submit" name="submit">Ajouter l'Article</button>
    </form>
</body>

</html>
