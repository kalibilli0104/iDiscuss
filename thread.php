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

$id=$_GET['threadid'];
$sql="SELECT * FROM `threads` where thread_id=$id";
$result=mysqli_query($conn,$sql);
while($row=mysqli_fetch_assoc($result)) {

  $title=$row['thread_title'];
  $desc=$row['thread_desc'];
  $thread_user_id=$row['thread_user_id'];

  //Query the users table to find out the name of original poster.
  $sql2="SELECT user_email from `users` where sno='$thread_user_id'";
  $result2=mysqli_query($conn,$sql2);
  $row2=mysqli_fetch_assoc($result2);
  $posted_by=$row2['user_email'];
}

?>

<?php
$showAlert=false;
$method=$_SERVER['REQUEST_METHOD'];
if($method=='POST')
{
  //inserting comment into db
  $comment=$_POST['comment'];
  $comment=str_replace("<","&lt;",$comment);
  $comment=str_replace(">","&gt;",$comment);
  $sno=$_POST['sno'];
  $sql="insert into `comments` (comment_content,thread_id,comment_by,comment_time) values ('$comment','$id','$sno',current_timestamp())";
  $result=mysqli_query($conn,$sql);
  $showAlert=true;
  if($showAlert)
  {
    echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Your comment has been added...
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
          <h2> <?php echo $title ?></h2>
          <p>
          <?php echo $desc ?>
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
          <p><em>Posted By: <?php echo $posted_by;  ?></em></p>
        </div>
      </div>
    </div>
    </div>

<?php


if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true)
{
echo '
<div class="container">
<h1>Post a Comment</h1>
<form action="'. $_SERVER['REQUEST_URI']  .'" method="post">
  <div class="mb-3">
  <label for="exampleFormControlTextarea1" class="form-label">Type your Comment</label>
  <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
<input type="hidden" name="sno" value="'.$_SESSION["sno"].'">

</div>
  <button type="submit" class="btn btn-success">Post Comment</button>
</form>
</div>';}
else
{

  echo '
  <div class="container">
  <h1>Post a Comment...</h1>
  <p class="lead">You are not logged in. Please login to be able to start a discussion.</p>
</div>';

}
?>


<div class="container mb-5">
    <h1 class="py-3">Discussion</h1>

    <?php
    $id = $_GET['threadid'];

    // Pagination setup
    $limit = 5; // comments per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Fetch comments with LIMIT
    $sql = "SELECT * FROM `comments` WHERE thread_id = $id LIMIT $offset, $limit";
    $result = mysqli_query($conn, $sql);
    $noResult = true;

    while ($row = mysqli_fetch_assoc($result)) {
        $noResult = false;
        $comment_id = $row['comment_id'];
        $content = $row['comment_content'];
        $comment_time = $row['comment_time'];
        $comment_by = $row['comment_by'];

        // Get commenter email
        $sql2 = "SELECT user_email FROM `users` WHERE sno = '$comment_by'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $user_email = $row2 ? $row2['user_email'] : "Anonymous User";

        echo '
        <div class="d-flex">
            <div class="flex-shrink-0 my-2">
                <img src="user.jpg" alt="..." width="60px">
            </div>
            <div class="flex-grow-1 ms-3 my-4">
                <p class="my-0" style="font-weight:bold;">Asked by :) ' . $user_email . ' at ' . $comment_time . '</p>
                ' . $content . '
            </div>
        </div>';
    }

    if ($noResult) {
        echo '
        <div class="card text-bg-secondary">
            <div class="card-header">
                <p style="font-size: 44px;">No Comments found</p>
                <hr>
                <b>Be the first person to ask a question.</b>
            </div>
        </div>';
    }

    // Pagination logic
    $sql_total = "SELECT COUNT(*) AS total FROM `comments` WHERE thread_id = $id";
    $result_total = mysqli_query($conn, $sql_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_comments = $row_total['total'];
    $total_pages = ceil($total_comments / $limit);

    // Page navigation
    if ($total_pages > 1) {
        echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center mt-4">';

        // Previous button
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="?threadid=' . $id . '&page=' . ($page - 1) . '">Previous</a></li>';
        }

        // Next button
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="?threadid=' . $id . '&page=' . ($page + 1) . '">Next</a></li>';
        }

        echo '</ul></nav></div>';
    }


  if($noResult)
  {
    echo '
    <div class="card text-bg-secondary">
  <div class="card-header">
    <p style="font-size: 44px;">No Comments found</p>
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
