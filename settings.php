<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Socialize</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        require_once('functions.php');
        session_start();
        $accountId = $_SESSION['account'];
        if($accountId) {
            include 'header.php';
    ?>
        <br>
        <form action="settings.php" method="POST" enctype="multipart/form-data" style="width: 50%;" class="mx-auto" id="settingsForm">
            <label class="form-label" for="profileImage">Change Profile Image:</label>
            <input type="file" class="form-control form-control-lg" id="profileImage" name="profileImage">
            <br>
            <button type="submit" class="btn btn-primary btn-lg" name="changePfpBtn" id="changePfpBtn">Change Profile Image</button><br><br>
            <button type="submit" class="btn btn-primary btn-lg" name="deactivateBtn" id="deactivateBtn">Deactivate Account</button>
        </form>
    <?php
        } else {
            header('Location: login.php');
        }
    ?>

    <?php
        if(isset($_POST['changePfpBtn'])) {
            $profileImage = $_FILES['profileImage'];

            if(empty($profileImage)) {
                $postImage = NULL;
                showErrorMessage("The image is invalid.");
            } else {

                $query = "SELECT `image` FROM `accounts` WHERE `accountId` = $accountId;";
                $res = mysqli_query(connectDB(), $query);
                while($row = mysqli_fetch_assoc($res)) {
                    $oldfile = $row['image'];

                    $uploaddir = 'img/profiles/';
                    //remove the old file from directory.
                    unlink($uploaddir . $oldfile);

                    $uploadfile = $uploaddir . basename($_FILES['profileImage']['name']);
                    //upload the file to directory.
                    move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadfile);

                    $updatequery = "UPDATE `accounts` SET `image` = '" . basename($_FILES['profileImage']['name']) . "' WHERE `accountId` = $accountId;";
                    mysqli_query(connectDB(), $updatequery);
                }


            }
        }

        if(isset($_POST['deactivateBtn'])) {
            //delete from accounts using the session id.
            $query = "DELETE FROM `accounts` WHERE `accountId` = $accountId;";
            mysqli_query(connectDB(), $query);
            $query2 = "DELETE FROM `posts` WHERE `accountId` = $accountId;";
            mysqli_query(connectDB(), $query2);
            include 'logout.php';
        }
        
        function showErrorMessage($errormsg) {
            echo '<div class'.'="alert alert-danger" role="alert">'.$errormsg.'</div>';
        };
    ?>
</body>
</html>