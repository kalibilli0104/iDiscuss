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
        min-height:87vh;
      }
    </style>
  </head>
  <body>
  <?php include 'partial/_dbconnect.php' ?>
    
    <!-- PHP Header Include -->
    <?php include 'partial/_header.php'; ?>

    <?php
    
    $method=$_SERVER['REQUEST_METHOD'];
    if($method=='POST')
    {
      $cemail=$_POST['cemail'];
      $concern=$_POST['concern'];

      $cemail=str_replace("<","&lt;",$cemail);
      $concern=str_replace(">","&gt;",$concern);
      $sql="INSERT INTO `contact` (`cemail`, `concern`) VALUES ('$cemail', '$concern')";
      $result=mysqli_query($conn,$sql);
      if($result)
      {
        echo "concern submitted";
      }
    }
    
    ?>

    <div class="container my-4">
      <h2 class="text-center">Contact Us</h2>
  <form action="/contact.php" method="post">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" id="cemail" name="cemail" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
  <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
  <textarea class="form-control" id="concern" name="concern" rows="3"></textarea>
</div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

   
    <!-- PHP Footer Include -->
    <?php include 'partial/_footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- Pexels API Script -->
    <script>
      const API_KEY = 'Qd2RaBg72i3vEo7lLm6qwVSzw1G5Mb7K7fDvwDKpj0MgUROW6cGOxBuT';
      const imageElements = document.querySelectorAll('.pexels-image');

      fetch('https://api.pexels.com/v1/search?query=codes&per_page=' + imageElements.length, {
        headers: {
          Authorization: API_KEY
        }
      })
      .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
      })
      .then(data => {
        imageElements.forEach((img, index) => {
          if (data.photos[index]) {
            img.src = data.photos[index].src.medium;
          } else {
            img.alt = "No image found";
          }
        });
      })
      .catch(error => {
        console.error('Error fetching images from Pexels:', error);
        imageElements.forEach(img => {
          img.alt = "Error loading image";
        });
      });
    </script>
  </body>
</html>
