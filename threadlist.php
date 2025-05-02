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
      #ques{
        min-height:433px;
      }

    </style>
  </head>
  <body>
    
    <!-- PHP Header Include -->
    <?php include 'partial/_dbconnect.php' ?>

    <?php include 'partial/_header.php'; ?>


<?php

$id=$_GET['catid'];
$sql="SELECT * FROM `categories` where category_id=$id";
$result=mysqli_query($conn,$sql);
while($row=mysqli_fetch_assoc($result)) {

  $catname=$row['category_name'];
  $catdesc=$row['description'];

}

?>


<?php
$showAlert=false;
$method=$_SERVER['REQUEST_METHOD'];
if($method=='POST')
{
  //inserting thread into db
  $th_title=$_POST['title'];
  $th_desc=$_POST['desc'];
  $sno=$_POST['sno'];

  $th_title=str_replace("<","&lt;",$th_title);
  $th_title=str_replace(">","&gt;",$th_title);

  $th_desc=str_replace("<","&lt;",$th_desc);
  $th_desc=str_replace(">","&gt;",$th_desc);

  $sql="insert into `threads` (thread_title,thread_desc,thread_cat_id,thread_user_id,timestamp) values ('$th_title','$th_desc','$id','$sno',current_timestamp())";
  $result=mysqli_query($conn,$sql);
  $showAlert=true;
  if($showAlert)
  {
    echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Your thread has been added please wait for community to respond
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    ';
  }
}

?>



    <!-- Main Content -->
    <div class="container mt-5 my-5" id="ques">
    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <div class="h-100 p-5 text-bg-dark rounded-3">
          <h2>Welcome to <?php echo $catname ?> forum</h2>
          <p>
          <?php echo $catdesc ?>
          </p>
          <hr>
          <p>
            This os a peer to peer forum for sharing knowledge with each other.

            Stay on topic and don't post irrelevant links, comments, thoughts, or pictures.
Don't type in ALL CAPS.
Don't write anything that sounds angry or sarcastic, even as a joke, because without hearing your tone of voice, your peers might not realize you're joking.
Be respectful, even when there's a disagreement.
No foul language or discriminatory comments.
No spam or self-promotion.
No links to external websites or companies.
No NSFW (not safe for work) content.
          </p>
          <button class="btn btn-outline-light btn-primary" type="button">Browse Topic</button>
        </div>
      </div>
    </div>
    </div>


 <?php   
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']=="true"){
echo ' 
<div class="container">

<h1>Start a Discussion...</h1>

<form action="'.$_SERVER["REQUEST_URI"].'" method="post">
  <div class="mb-3">
    <label for="title" class="form-label">Problem Title</label>
    <input type="text" class="form-control" id="title" name="title">
    <div class="form-text">Keep your title as short and crisp as possible.</div>
  </div>
  <div class="mb-3">
  <label for="exampleFormControlTextarea1" class="form-label">Ellaborate your concern</label>
  <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
  
</div>
<input type="hidden" name="sno" value="'.$_SESSION["sno"].'">
  <button type="submit" class="btn btn-success">Submit</button>
</form>

</div>';}
else
{
  echo '
  <div class="container">
  <h1>Start a Discussion...</h1>
  <p class="lead">You are not logged in. Please login to be able to start a discussion.</p>
</div>';
 
}
?>



<div class="container mb-5">
  <h1 class="py-3">Browse Questions</h1>

  <?php
  $id = $_GET['catid'];

  // Pagination setup
  $limit = 5;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if($page < 1) $page = 1;
  $offset = ($page - 1) * $limit;

  // Count total records
  $countSql = "SELECT COUNT(*) AS total FROM `threads` WHERE thread_cat_id=$id";
  $countResult = mysqli_query($conn, $countSql);
  $totalThreads = mysqli_fetch_assoc($countResult)['total'];
  $totalPages = ceil($totalThreads / $limit);

  // Fetch limited threads
  $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id ORDER BY timestamp DESC LIMIT $limit OFFSET $offset";
  $result = mysqli_query($conn, $sql);
  $noResult = true;

  while($row = mysqli_fetch_assoc($result)) {
    $noResult = false;
    $thread_id = $row['thread_id'];
    $title = $row['thread_title'];
    $desc = $row['thread_desc'];
    $thread_time = $row['timestamp'];
    $thread_user_id = $row['thread_user_id'];

    $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    $user_email = $row2 ? $row2['user_email'] : "Anonymous User";

    echo '<div class="d-flex">
      <div class="flex-shrink-0 my-2">
        <img src="user.jpg" alt="..." width="60px">
      </div>
      <div class="flex-grow-1 ms-3 my-4">
        <h5 class="mt-0"><a class="text-dark" href="thread.php?threadid='.$thread_id.'">'.$title.'</a></h5>
        '.$desc.'
        <p class="my-0" style="font-weight:bold;">Asked by :) '.$user_email.' at '.$thread_time.'</p>
      </div>
    </div>';
  }

  if($noResult) {
    echo '
      <div class="card text-bg-secondary">
        <div class="card-header">
          <p style="font-size: 44px;">No Threads found</p>
          <hr>
          <b>Be the first person to ask a question.</b>
        </div>
      </div>';
  }

  // Pagination Buttons
  echo '<nav aria-label="Page navigation example" class="mt-4">
          <ul class="pagination justify-content-center">';

  if($page > 1){
    echo '<li class="page-item">
            <a class="page-link" href="?catid='.$id.'&page='.($page - 1).'">Previous</a>
          </li>';
  }

  for($i = 1; $i <= $totalPages; $i++) {
    $active = $i == $page ? 'active' : '';
    echo '<li class="page-item '.$active.'"><a class="page-link" href="?catid='.$id.'&page='.$i.'">'.$i.'</a></li>';
  }

  if($page < $totalPages){
    echo '<li class="page-item">
            <a class="page-link" href="?catid='.$id.'&page='.($page + 1).'">Next</a>
          </li>';
  }

  echo '</ul></nav>';
  ?>
</div>


  if($noResult)
  {
    echo '
    <div class="card text-bg-secondary">
  <div class="card-header">
    <p style="font-size: 44px;">No Threads found</p>
    <hr>
    <b>Be the first Persorn to ask a Question.</b>
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
