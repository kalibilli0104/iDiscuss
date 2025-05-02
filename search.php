<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iDiscuss - Coding Forum</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <style>
      .card-img-custom {
        width: 100%;
        height: 300px;
        object-fit: cover;
      }
      .container{
        min-height: 433px;
      } 
   </style>
  </head>
  <body>
    
    <!-- PHP Header Include -->
    <?php include 'partial/_dbconnect.php' ?>


    <?php include 'partial/_header.php'; ?>


    
  
    <!-- Search result starts here  -->
     <div class="container my-3">
     <h1 class="py-3">Search Results for <em>"<?php echo $_GET['search']  ?>"</em></h1>
     <?php
    $noResults=true;
    $query=$_GET["search"];
    $sql="SELECT * from threads where MATCH (thread_title,thread_desc) against ('$query')";
    $result=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($result))
    {
      $title=$row['thread_title'];
      $desc=$row['thread_desc'];
      $thread_id=$row['thread_id'];
      $url="thread.php?threadid=".$thread_id;
      $noResults=false;

      echo '
      
      <div class="result my-4">
            <h3>
                <a href="'.$url.'" class="text-dark">'.$title.'</a>
            </h3>
            <p>'.$desc.'</p>
        </div>
     </div>

      ';
    }
    if($noResults)
    {
      echo '
    <div class="card text-bg-secondary">
  <div class="card-header">
    <p style="font-size: 44px;">No Results found</p>
    <hr>
    <p class="lead">Suggestions: 
    <ul>
      <li>Make sure that all words are spelled correctly.</li>
      <li>Try different keywords.</li>
      <li>Try more general keywords.</li>
    </ul>
  </div>
  </div>
    ';
    }
    ?>

        



    <!-- PHP Footer Include -->
    <?php include 'partial/_footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- Pexels API Script -->
    <script>
      const API_KEY = 'Qd2RaBg72i3vEo7lLm6qwVSzw1G5Mb7K7fDvwDKpj0MgUROW6cGOxBuT';
const imageElements = document.querySelectorAll('.pexels-image');

imageElements.forEach(img => {
  const category = img.getAttribute('data-category');

  fetch(`https://api.pexels.com/v1/search?query=${encodeURIComponent(category)}&per_page=1`, {
    headers: {
      Authorization: API_KEY
    }
  })
  .then(response => {
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    return response.json();
  })
  .then(data => {
    if (data.photos.length > 0) {
      img.src = data.photos[0].src.medium;
    } else {
      img.alt = "No image found";
    }
  })
  .catch(error => {
    console.error('Error fetching image from Pexels:', error);
    img.alt = "Error loading image";
  });
});

    </script>

  </body>
</html>
