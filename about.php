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
