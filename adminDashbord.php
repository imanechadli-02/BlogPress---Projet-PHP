<?php
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    die("Error: User is not logged in.");
}

$user_id = $_SESSION['id'];

$query = "
    SELECT 
        COUNT(*) AS total_articles,
        SUM(article_views) AS total_views,
        SUM(article_likes) AS total_likes
    FROM articles
    WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$stats = mysqli_fetch_assoc($result);

$chart_query = "
    SELECT 
        a.article_id, 
        a.article_titre, 
        a.article_views AS article_views, 
        a.article_likes AS article_likes, 
        COUNT(c.comment_id) AS total_comments
    FROM articles a
    JOIN comments c ON a.article_id = c.article_id
    WHERE a.user_id = $user_id
    GROUP BY a.article_id, a.article_titre, a.article_views, a.article_likes";
$chart_result = mysqli_query($conn, $chart_query);

if (!$chart_result) {
    die("Query failed: " . mysqli_error($conn));
}

$chart_data = [];
while ($row = mysqli_fetch_assoc($chart_result)) {
    $chart_data[] = $row;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid #eaeaea;
        }

        header .flex {
            display: flex;
            align-items: center;
        }

        header a {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4a90e2;
            text-decoration: none;
        }

        /* Styling the Logout Button */
        button[type="submit"] {
            padding: 12px 24px;
            font-size: 1.1rem;
            background-color: #e74c3c;
            /* Red background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        button[type="submit"]:hover {
            background-color: #c0392b;
            /* Darker red on hover */
            transform: translateY(-2px);
            /* Slight lift effect */
        }

        button[type="submit"]:active {
            background-color: #a93226;
            /* Even darker red on click */
            transform: translateY(0);
            /* Normal position when clicked */
        }

        button[type="submit"]:focus {
            outline: none;
            border: 2px solid #d35400;
            /* Orange border on focus */
        }

        .dashboard {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: #4a90e2;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 20px;
            background: #eef5ff;
            border-radius: 10px;
        }

        .stats div {
            font-size: 1.2rem;
            color: #555;
            text-align: center;
        }

        .stats div:nth-child(odd) {
            color: #4a90e2;
            font-weight: bold;
        }

        .chart-container {
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
            width: 90%;
            max-width: 800px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background: #4a90e2;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background: #357ab8;
        }

        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                gap: 10px;
            }

            .chart-container {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header class="flex justify-between items-center p-4 bg-white shadow-md">
        
        <div>
            <form method="POST" action="">
                <button type="submit" name="logout" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Logout</button>
            </form>
        </div>
    </header>

    <div class="dashboard">
        <h1>Welcome to Your Dashboard</h1>
        <div class="stats">
            <div>Total Articles: <?= $stats['total_articles'] ?></div>
            <div>Total Views: <?= $stats['total_views'] ?></div>
            <div>Total Likes: <?= $stats['total_likes'] ?></div>
        </div>

        <div class="chart-container">
            <canvas id="viewChart"></canvas>
        </div>

        <a href="manageArticles.php">Manage Articles</a>
        <a href="add_articles.php">Add Article</a>
    </div>

    <script>
        const chartData = <?= json_encode($chart_data) ?>;
        const labels = chartData.map(item => item.article_titre); 
        const views = chartData.map(item => item.article_views); 
        const likes = chartData.map(item => item.article_likes); 
        const comments = chartData.map(item => item.total_comments); 

        const ctx = document.getElementById('viewChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Views',
                        data: views,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Likes',
                        data: likes,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Comments',
                        data: comments,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    </script>
</body>

</html>