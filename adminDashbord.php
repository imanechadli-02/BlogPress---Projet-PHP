<?php
session_start();
include("config.php");

// Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     // Redirect to login page if not logged in
//     header('Location: signIn.php');
//     exit();
// }

// Get the logged-in author ID

$user_id = $_SESSION['id'];
echo $_SESSION['id'];  // Check the session ID value after setting


// Fetch article statistics
$query = "
    SELECT 
        COUNT(*) AS total_articles,
        SUM(article_views) AS total_views,
        SUM(article_likes) AS total_likes
    FROM articles
    WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$stats = mysqli_fetch_assoc($result);

// Fetch view trends for Chart.js
$chart_query = "
    SELECT DATE(article_at) AS date, SUM(article_views) AS views
    FROM articles
    WHERE user_id = $user_id
    GROUP BY DATE(article_at)";
$chart_result = mysqli_query($conn, $chart_query);

$chart_data = [];
while ($row = mysqli_fetch_assoc($chart_result)) {
    $chart_data[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
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

        /* Statistics Section */
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

        /* Chart Section */
        .chart-container {
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
            width: 90%;
            max-width: 800px;
        }

        /* Link Style */
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

        /* Responsive Design */
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

    <!-- <script src="js/chart.js"></script>
    <script>
        const chartData =
          <?= json_encode($chart_data) ?>; -->
        <!--const labels = chartData.map(item => item.date);
        const data = chartData.map(item => item.views);

        const ctx = document.getElementById('viewChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Views Over Time',
                    data: data,
                    borderColor: 'blue',
                    fill: false
                }]
            },
        });
    </script> -->
</body>

</html>