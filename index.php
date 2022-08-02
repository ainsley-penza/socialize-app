<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Socialize</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

</head>
<body>
<?php require_once('functions.php'); ?>
<?php
    session_start();
    if($_SESSION["account"]) {
        //account logged in
        ?>
        <?php include 'header.php'; ?>

        <br>

        <div id="createPost">
            <form action="statuspost.php" method="POST" enctype="multipart/form-data" id="statusPostForm" class="mx-auto" style="width: 50%;">
                <label for="postContent" class="form-label">Create a post:</label>
                <textarea id="postContent" class="form-control" name="postContent" placeholder="Write something here."></textarea>

                <label for="postImage" class="form-label">Add an image:</label>
                <input type="file" class="form-control form-control-sm" name="postImage" id="postImage">
                <br>
                <button type="submit" action="statuspost.php" class="btn btn-primary btn-sm" name="postBtn" id="postBtn">Post</button>
            </form>
        </div>
        <br>

        <main id="posts">
            <?php 
                function getAllPosts() {
                    $query = "SELECT * FROM `posts` ORDER BY 6 DESC;";
                    $res = mysqli_query(connectDB(), $query);
                    while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                        $authorname = getAuthorNameFromID($row['accountId']);
                        $postid = $row['postId'];
                        $postContent = $row['postContent'];
                        $postImage = $row['postImage'];
                        $likes = $row['likes'];
                        $datetime = $row['datetime'];
            ?>
                    <div class="card text-center">
                        <div class="card-header">
                            <?php echo $authorname //check for author name?>
                        </div>
                        <div class="card-body" postid="<?php echo $postid //check for postid ?>">
                            <img class="card-image" width="300px;"
                            src="<?php echo 'img/posts/' . $postImage ?>" 
                            <?php
                                if($postImage == '') {
                            ?>
                                hidden
                            <?php
                                    
                                }
                            ?>/>
                            <p class="card-text"><?php echo $postContent ?></p>
                            <form action="index.php" method="POST" id="likeForm">
                                <input type="hidden" name="post-id" value="<?php echo $postid?>"> 
                                <button class="btn btn-primary likeBtn" type="submit" name="likeBtn"><?php echo $likes //check for likes?> 👍</button>
                            </form>
                        </div>
                        <div class="card-footer text-muted">
                            <?php echo $datetime //check for date and time of creation ?>
                        </div>
                    </div>

                    <br>
            <?php
                    }
                }

                getAllPosts();
            ?>
        </main>
    <?php
    } else {
        //account not logged in.
        header('Location: login.php');
    }
    ?>


    <script src="js/statuspost.js"></script>
</body>
</html>

<?php
    function getAuthorNameFromID($id) {
        $query = "SELECT `name`, `surname` FROM `accounts` WHERE `accountId` = $id";
        $res = mysqli_query(connectDB(), $query);
        while($row = mysqli_fetch_assoc($res)) {
            return $row['name'] . ' ' . $row['surname'];
        }
    }

    if(isset($_POST['likeBtn'])) {
        $postid = $_POST['post-id'];

        $query = "SELECT `likes` FROM `posts` WHERE `postId` = $postid;";
        $res = mysqli_query(connectDB(), $query);

        while($row = mysqli_fetch_assoc($res)) {
            $currLikes = $row['likes'];
            $newLikes = $currLikes+1;

            //update likes.
            $query = "UPDATE `posts` SET `likes` = $newLikes WHERE `postId` = $postid";
            mysqli_query(connectDB(), $query);
        }

        //refresh page
        echo '<script></script>';
    }
?>