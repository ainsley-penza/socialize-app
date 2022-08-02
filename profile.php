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
    <?php require_once('functions.php'); ?>
    <?php
        session_start();
        $accountId = $_SESSION['account'];

        if($accountId) {
            include 'header.php';
            
            $query = "SELECT * FROM `accounts` WHERE `accountId` = $accountId;";
            $res = mysqli_query(connectDB(), $query);
            while($row = mysqli_fetch_assoc($res)) {
                $countryquery = "SELECT `name` FROM `countries` WHERE `countryId` = " . $row['countryId'] . ";";
                $countryres = mysqli_query(connectDB(), $countryquery);
                $countryname = '';
                while($countryrow = mysqli_fetch_assoc($countryres)) {
    ?>
                <div class="card" style="width: 18rem; margin: auto;">
                    <img class="card-img-top" src="<?php echo 'img/profiles/' . $row['image']; ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name'] . ' ' . $row['surname']; ?></h5>
                        <p class="card-text">Country: <?php echo $countryrow['name']; ?>
                        <br>
                        Mobile Number: <?php echo $row['mobile']; ?>
                        <br>
                        Gender: <?php 
                        switch($row['genderId']) {
                            case 1:     echo 'Male';     break;
                            case 2:     echo 'Female';   break;
                            case 3:     echo 'Other';    break;
                        };
                        ?>
                        <br>
                        Date of Birth: <?php 
                        $dateofbirth = date_create($row['dateOfBirth']);
                        echo $dateofbirth->format('jS F Y'); 
                        ?></p>
                        <a href="settings.php" class="btn btn-primary">Change Settings</a>
                    </div>
                </div>
    <?php
                }
            }
        } else {
            header('Location: login.php');
        }
    ?>
</body>
</html>