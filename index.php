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
    </style>
  </head>
  <body>
    
    <!-- PHP Header Include -->
    <?php include 'partial/_dbconnect.php' ?>


    <?php include 'partial/_header.php'; ?>


    <!-- Carousel -->
    <div id="carouselExample" class="carousel slide">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="pexels-image d-block w-100" height="800px" alt="..." data-category="communicating">
        </div>
        <div class="carousel-item">
          <img class="pexels-image d-block w-100" alt="..." height="800px" data-category="innovation">
        </div>
        <div class="carousel-item">
          <img class="pexels-image d-block w-100" alt="..." height="800px" data-category="read">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <!-- Main Content -->
    <div class="container mt-5 my-5">
      <h2 class="mb-4 text-center my-4">Welcome to iDiscuss - Browse Categories</h2>

      <div class="row my-4">
        <!-- Example category card -->

        <?php 
$sql="SELECT * FROM `categories`";
$result=mysqli_query($conn,$sql);
while($row=mysqli_fetch_assoc($result)) {
  $id=$row['category_id'];
  $cat = $row['category_name'];
  $desc=$row['description'];
  echo '<div class="col-md-4 my-2">
    <div class="card" style="width: 18rem;">
      <img class="pexels-image card-img-top card-img-custom" alt="Nature image" data-category="'.htmlspecialchars($cat).'">
      <div class="card-body">
        <h5 class="card-title"><a href="http://threadlist.php?catid='.$id.'">'.htmlspecialchars($cat).'</a></h5>
        <p class="card-text">
        '.substr($desc,0,90).'.....
        </p>
        <a href="http://threadlist.php?catid='.$id.'" class="btn btn-primary">View Threads</a>
      </div>
    </div>
  </div>';
}
?>


        
      </div>
    </div>

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
