<?php
session_start();
require_once('../connection/connection.php');

$user_id = $_SESSION['user_id'];
$request_id = $_GET['request_id'];
$queryForum = "SELECT f.*, u.username FROM forum f JOIN users u ON f.user_id = u.id WHERE f.request_id = $request_id";
$resultForum = $conn->query($queryForum);

$forumData = $resultForum->fetch_assoc();




$queryComments ="SELECT c.comments, DATE(c.time) as comment_date, u.username 
FROM comments c 
JOIN users u ON c.user_id = u.id 
WHERE c.request_id = $request_id";




// $queryComments = "SELECT comments, time FROM comments WHERE request_id = $request_id";
$resultComments = $conn->query($queryComments);

$comments = array();
while ($row = $resultComments->fetch_assoc()) {
    $comments[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$commentss = $_POST['comments'];
$timestamp = date("Y-m-d H:i:s");
$insertCommentQuery = "INSERT INTO comments (user_id, request_id, comments, time) VALUES ('$user_id', '$request_id', '$commentss', '$timestamp')";
    if ($conn->query($insertCommentQuery) === TRUE) {
        // echo "Comment added successfully";
    } else {
        echo "Error adding comment: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="comments.css">
    <title>add comments</title>
</head>

<body>
<div class="upper" id="upper-part">
            <!-- ... (your existing navigation bar) ... -->
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><i class='bx bxs-truck'></i></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <a class="nav-link active" aria-current="page" href="#">
                                <div class="waviy">
                                    <span style="--i:1">D</span>
                                    <span style="--i:2">0</span>
                                    <span style="--i:3">0</span>
                                    <span style="--i:4">D</span>
                                    <span style="--i:5">L</span>
                                    <span style="--i:6">E</span>
                                    <span style="--i:7">.</span>

                                </div>
                            </a>
                            <a class="nav-link" href="forum.php">back </a>
                            <a class="nav-link" href="#">start selling </a>

                        </div>
                    </div>
                </div>
            </nav>
        </div>





    <div class="box">
        <div class="info">
            <div class="info-box">
                <p>Username:
                    <?php echo $forumData['username']; ?>
                </p>
                <p>Product Name:
                    <?php echo $forumData['product_name']; ?>
                </p>
                <p>Details:
                    <?php echo $forumData['details']; ?>
                </p>
            </div>
            <div class="input-group mb-3">
                <form method="post" action="" class="">
                    <input type="text" class="form-control" placeholder="add comment" name="comments"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">add comment</button>
                    </div>
                </form>
            </div>

        </div>
        <div class="main">
            <h3>comments</h3>
            <div class="comment-box">
                <?php if (is_array($comments) && count($comments) > 0) : ?>
                <?php foreach ($comments as $comment) : ?>
                <div class="commenter">
                    <div class="commenter-info">
                        <p>
                            <?php echo $comment['username']; ?>
                        </p>
                    </div>
                    <div class="user-comments">
                        <p>
                            <?php echo $comment['comments']; ?>
                        </p>
                        <p class="date">
                            <?php echo $comment['comment_date']; ?>
                        </p>

                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>No comments available.</p>

                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
    </div>
</body>

</html>