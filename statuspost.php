<?php require_once('functions.php'); ?>

<?php

    echo 'Hello';
    session_start();
    if(isset($_POST['postContent']) || isset($_POST['postImage'])) {
        $postContent = $_POST['postContent'];
        $postImage = $_FILES['postImage'];
        if(empty($postImage)) {
            $postImage = NULL;
        } else {
            $uploaddir = 'img/posts/';
            $uploadfile = $uploaddir . basename($_FILES['postImage']['name']);

            move_uploaded_file($_FILES['postImage']['tmp_name'], $uploadfile);
        }

        createPost($postContent, basename($_FILES['postImage']['name']));
    }

    function createPost($postContent, $postImage) {
        $dtnow = date("Y-m-d H:i:s");
        $accountId = $_SESSION["account"];
        $connection = connectDB();

        $query = "INSERT INTO `posts` (`postId`, `accountId`, `likes`, `postContent`, `datetime`, `postImage`) ";
        $query .= "VALUES (NULL, '$accountId', 0, '$postContent', '$dtnow', ";
        if($postImage == NULL) {
            $query .= "NULL);";
        } else {
            $query .= "'$postImage');";
        }

        if(mysqli_query($connection, $query)) {
            header('Location: index.php');
        } else {
            echo "There was an error creating the message: " . mysqli_error($connection);
        }
    }
?>