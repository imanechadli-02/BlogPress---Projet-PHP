<?php
include "config.php";

$query = "SELECT * FROM articles
          JOIN auteur 
          ON articles.user_id = auteur.user_id  
          ORDER BY article_at DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

  <header class='flex shadow-[0px_0px_16px_rgba(17,_17,_26,_0.1)] py-4 px-4 sm:px-6 bg-white font-sans min-h-[70px] tracking-wide relative z-50'>
    <div class='flex flex-wrap items-center justify-between gap-4 w-full max-w-screen-xl mx-auto'>
      <a href="javascript:void(0)" class="max-sm:hidden"><img src="uploads/logo-removebg-preview.png" alt="logo" class='w-36' />
      </a>
      <a href="javascript:void(0)" class="hidden max-sm:block"><img src="https://readymadeui.com/readymadeui-short.svg" alt="logo" class='w-9' />
      </a>

      <!-- Add Sign In and Sign Up buttons -->
      <div class="flex gap-4 items-center">
        <a href="signin.php" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 transition-all duration-300">Sign In</a>
        <a href="signup.php" class="px-4 py-2 text-white bg-green-500 rounded-md hover:bg-green-600 transition-all duration-300">Sign Up</a>
      </div>
    </div>
  </header>

  <div class="bg-white px-4 py-10 font-sans">
    <div class="max-w-6xl max-lg:max-w-3xl max-sm:max-w-sm mx-auto">
      <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-800">LATEST BLOGS</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-sm:gap-8 mt-12">

        <?php if ($result->num_rows > 0): ?>
          <!-- Loop through each article -->
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="overflow-hidden p-4 rounded-md hover:bg-purple-100 transition-all duration-300">
              <img src="uploads/<?php echo $row['article_img']; ?>" alt="Blog Post" class="w-full h-64 object-cover rounded-md" />
              <div class="text-center">
                <span class="text-sm block text-gray-800 mb-2 mt-4"><?php echo date('d M Y', strtotime($row['article_at'])); ?> | BY <?php echo $row['user_fname']; ?> <?php echo $row['user_lname']; ?> </span>
                <h3 class="text-xl font-bold text-gray-800 mb-4"><?php echo $row['article_titre']; ?></h3>
                <p class="text-gray-800 text-sm"><?php echo substr($row['article_content'], 0, 20); ?>...</p>
                <a href="single-post.php?id=<?php echo $row['article_id']; ?>" class="px-5 py-2.5 text-white text-sm tracking-wider border-none outline-none rounded-md bg-purple-500 hover:bg-purple-600 mt-6">Read more</a>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-gray-800">No articles found.</p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</body>

</html>