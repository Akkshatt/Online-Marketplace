<?php
session_start();
require_once('../connection/conn.php');

$user_id = $_SESSION['user_id'];
// Function to get products with comments and replies from the database
function getProducts() {
    $conn = connectToDatabase();
    $sql = "SELECT r.*, c.comment_id, c.comment_text, u.username as commenter_username
                FROM requests r
                LEFT JOIN comments c ON r.request_id = c.request_id
                LEFT JOIN users u ON c.user_id = u.id
                ORDER BY r.request_id, c.comment_id";
    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!isset($products[$row['request_id']])) {
                $products[$row['request_id']] = $row;
                $products[$row['request_id']]['comments'] = [];
            }

            if ($row['comment_id'] !== null) {
                $comment = [
                    'comment_id' => $row['comment_id'],
                    'comment_text' => $row['comment_text'],
                    'commenter_username' => $row['commenter_username'],
                ];

                $products[$row['request_id']]['comments'][] = $comment;
            }
        }
    }

    $conn->close();

    return $products;
}

function getreviews($commentId) {
    $conn = connectToDatabase();
    $sql = "SELECT cr.reply_id, cr.reply_text, cr.user_id as replier_id, u.username as replier_name
            FROM comment_replies cr
            LEFT JOIN users u ON cr.user_id = u.id
            WHERE cr.comment_id = $commentId
            ORDER BY cr.reply_id";

    $result = $conn->query($sql);

    $replies = [];  // Initialize an empty array to store replies

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Only add non-null replies to the array
            if ($row['reply_id'] !== null) {
                $reply = [
                    'reply_id' => $row['reply_id'],
                    'reply_text' => $row['reply_text'],
                    'replier_id' => $row['replier_id'],
                    'replier_name' => $row['replier_name']
                ];

                // Add the reply to the array
                $replies[] = $reply;
            }
        }
    }

    $conn->close();

    return $replies;
}

// Function to save a new reply to a comment in the database
function saveReply($commentId, $replyText, $userId) {
    $conn = connectToDatabase();
    $sql = "INSERT INTO comment_replies (comment_id, user_id, reply_text) VALUES ($commentId, $userId, '$replyText')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Function to save a new comment to the database
function saveComment($requestId, $commentText, $userId) {
    $conn = connectToDatabase();
    $sql = "INSERT INTO comments (request_id, comment_text, user_id) VALUES ($requestId, '$commentText', $userId)";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Function to save a new product to the database
function saveProduct($productName, $productDescription, $productImage, $userId) {
    $conn = connectToDatabase();
    if(!$productImage){
    $sql = "INSERT INTO requests (name, description, upvotes, user_id) VALUES (?, ?, 0, ?)";
    } else {
    $sql = "INSERT INTO requests (name, description, image, upvotes, user_id) VALUES (?, ?, ?, 0, ?)";
    }
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        if (isset($productImage)) {
            $stmt->bind_param("sssi", $productName, $productDescription, $productImage, $userId);
        } else {
            $stmt->bind_param("ssi", $productName, $productDescription, $userId);
        }

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Request saved successfully');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}

// Function to handle upvoting
function upvoteProduct($productId, $userId) {
    $conn = connectToDatabase();

    $upvotedProducts = isset($_SESSION['upvotedProducts']) ? $_SESSION['upvotedProducts'] : [];

    if (!in_array($productId, $upvotedProducts)) {
        // Corrected the column name to 'upvotes'
        $updateSql = "UPDATE requests SET upvotes = upvotes + 1 WHERE request_id = ?";

        $stmt = $conn->prepare($updateSql);

        if ($stmt) {
            $stmt->bind_param("i", $productId);

            if ($stmt->execute()) {
                echo "Product liked successfully";
                $_SESSION['upvotedProducts'][] = $productId;
                header("Location: forum.php");
                exit();
            } else {
                echo "Error updating likes: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "You have already upvoted this product";
    }

    $conn->close();
}

// Handle product submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productName']) && isset($_POST['productDescription'])) {
    $productName = $_POST['productName'];
    $productDescription = $_POST['productDescription'];

    // Assuming you have a session variable for user ID
    $userId = $_SESSION['user_id'];

    // Handle image upload
    $imageData = NULL;

    if ($_FILES['productImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        $tmpName = $_FILES['productImage']['tmp_name'];
        $imageData = file_get_contents($tmpName);
    }

    // Save product to the database
    saveProduct($productName, $productDescription, $imageData, $userId);
}

// Handle upvoting
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["request_id"])) {
    $request_id = $_GET["request_id"];
    upvoteProduct($request_id, $user_id);
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentText']) && isset($_POST['requestId'])) {
    $commentText = $_POST['commentText'];
    $requestId = $_POST['requestId'];

    // Assuming you have a session variable for user ID
    $userId = $_SESSION['user_id'];

    saveComment($requestId, $commentText, $userId);
}

// Handle comment reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['replyText']) && isset($_POST['commentId'])) {
    $replyText = $_POST['replyText'];
    $commentId = $_POST['commentId'];

    // Assuming you have a session variable for user ID
    $userId = $_SESSION['user_id'];

    saveReply($commentId, $replyText, $userId);
}

// Get products with comments
$products = getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/boxicons/2.0.7/css/boxicons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
          <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:ital,wght@0,300;0,700;1,400&family=Saira+Extra+Condensed:wght@100;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="forum.css">
    <title>Product Request Page</title>
   
</head>
<body>
    <div class="dash">
    <div class="navb">
            <div class="nsec1">
          
            <div class="waviy">  <i class='bx bxs-truck'></i>
                                    <span style="--i:1">D</span>
                                    <span style="--i:2">0</span>
                                    <span style="--i:3">0</span>
                                    <span style="--i:4">D</span>
                                    <span style="--i:5">L</span>
                                    <span style="--i:6">E</span>
                                    <span style="--i:7">.</span>
                                </div>
            </div>
            <div class="nsec2">
                <a href="buyer.php">buyer</a>
                <a href="buyer_dashboard.php">profile</a>
                <a href="return.php">returns</a>

                <a href="./orders.php">orders</a>
            </div>
           
        </div>
<h1>Product Request Page</h1>

<div class="request-form">
<form method="POST" action="forum.php" enctype="multipart/form-data">
    <label for="productName">Product Name:</label>
    <input type="text" id="productName" name="productName" required>

    <label for="productDescription">Product Description:</label>
    <textarea id="productDescription" name="productDescription" rows="4" required></textarea>

    <label for="productImage">Product Image:</label>
    <input type="file" id="productImage" name="productImage" accept="image/*">

    <button type="submit">Submit Product</button>
</form>
</div>
   <div class="product-list">
  <?php foreach ($products as $product): ?>
    <div class="product-card">
    <img src="data:image/jpeg;base64,<?= base64_encode($product['image']); ?>" alt="<?= $product['name']; ?>" class="product-image">
        <div class="product-details">
            <h2><?= $product['name']; ?></h2>
            <p><?= $product['description']; ?></p>
            <button class="upvote-btn" onclick="upvoteProduct(<?= $product['request_id']; ?>)">
                Upvotes: <?= $product['upvotes']; ?>
            </button>
            <button class="comment-toggle" onclick="toggleCommentSection(<?= $product['request_id']; ?>)">
                <i class="fas fa-comment" style="color:#224ecd"></i>
            </button>
            <!-- Comment Section -->
        
            <!-- <div class="comment-section"> -->
            <div class="comment-section" id="commentSection<?= $product['request_id']; ?>" style="display: none;">

                <?php foreach ($product['comments'] as $comment): ?>
                 
                    <div class="comment">
                        <strong><?= $comment['commenter_username']; ?>:</strong> <?= $comment['comment_text']; ?>
                        
                        <!-- Display Replies -->
                        <?php $replies = getreviews($comment['comment_id']); ?>

                        <?php if (!empty($replies)): ?>
                            <?php foreach ($replies as $reply): ?>
                                <div class="reply">
                                    <strong><?= $reply['replier_name']; ?>:</strong> <?= $reply['reply_text']; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Reply Form -->
                        <form id="replyForm<?= $comment['comment_id']; ?>" method="POST" action="forum.php" style="display: none;">
                            <input type="hidden" name="commentId" value="<?= $comment['comment_id']; ?>">
                            <label for="replyText">Add Reply:</label>
                            <textarea id="replyText" name="replyText" rows="2" required></textarea>
                            <button type="submit">Submit Reply</button>
                        </form>
                        <button class="reply-toggle" onclick="toggleReplyForm(<?= $comment['comment_id']; ?>)">reply
                        <i class="fas fa-reply" style="color:#224ecd"></i>
</button>
                    </div>
                <?php endforeach; ?>
                
            </div>
            <button class="comment-toggle" onclick="toggleCommentForm(<?= $product['request_id']; ?>)">  <i class="fas fa-plus-square" style="color:#3254d9"></i></button>
            <!-- Comment Form -->
            <form id="commentForm<?= $product['request_id']; ?>" method="POST" action="forum.php" style="display: none;">
                <input type="hidden" name="requestId" value="<?= $product['request_id']; ?>">
                <label for="commentText">Add Comment:</label>
                <textarea id="commentText" name="commentText" rows="2" required></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
<script>
    function upvoteProduct(requestId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "forum.php?request_id=" + requestId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                window.location.reload();
            }
        };
        xhr.send();
    }
    function toggleCommentForm(requestId) {
        var commentForm = document.getElementById('commentForm' + requestId);
        commentForm.style.display = (commentForm.style.display === 'none') ? 'block' : 'none';
    }

    function toggleReplyForm(commentId) {
        var replyForm = document.getElementById('replyForm' + commentId);
        replyForm.style.display = (replyForm.style.display === 'none') ? 'block' : 'none';
    }
    function toggleCommentSection(commentId) {
    var commentSection = document.getElementById('commentSection' + commentId);
    commentSection.style.display = (commentSection.style.display === 'none') ? 'block' : 'none';
}

</script>

</body>
</html>
