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
    <?php require_once('functions.php') ?>

    <div class="p-3 mb-2 bg-primary text-white"><h2>Login to Socialize!</h2></div>

    <form id="form" action="login.php" method="POST" class="mx-auto" style="width: 50%;">
        <label class="form-label" for="emailmobile">Email or Mobile:</label>
        <input type="text" class="form-control" placeholder="johndoe@gmail.com OR 99854837" id="emailmobile" name="emailmobile">

        <label class="form-label" for="password">Password:</label>
        <input type="password" class="form-control" placeholder="Password" id="password" name="password">

        <br>
        <button type="submit" class="btn btn-primary btn-lg" name="loginBtn" id="loginBtn">Login</button>
        <br><br>
        <a href="register.php">Do not own an account? Register here.</a>
    </form>

    <div id="errors">
        <br><br>

        <?php
            if(isset($_POST['loginBtn'])) {
                $emailmobile = mysqli_real_escape_string(connectDB(), $_POST['emailmobile']);
                $password = mysqli_real_escape_string(connectDB(), $_POST['password']);

                if(!empty($emailmobile) && !empty($password)) {
                    if(filter_var($emailmobile, FILTER_VALIDATE_EMAIL)) {
                        if(!checkIfEmailExists($emailmobile)) {
                            //Email does not exist.
                            showErrorMessage('Email does not exist.');
                        } else {
                            //Email exists.
                            checkEmailPasswordMatching($emailmobile, $password);
                        }
                    } else {
                        if(!checkIfMobileExists($emailmobile)) {
                            //Mobile does not exist.
                            showErrorMessage('Mobile does not exist.');
                        } else {
                            //Mobile exists.
                            checkMobilePasswordMatching($emailmobile, $password);
                        }
                    }
                }
                
                if(empty($emailmobile)) {
                    showErrorMessage('Email/Mobile should contain a value.');
                }

                if(empty($password)) {
                    showErrorMessage('Password should contain a value.');
                }
            }

            function checkIfEmailExists($email) {
                $query = "SELECT `email` FROM `accounts` WHERE `email` = '$email';";
                $res = mysqli_query(connectDB(), $query);
                if($res->num_rows == 1){
                    return true;
                } else {
                    return false;
                }
            }
        
            function checkIfMobileExists($mobile) {
                $query = "SELECT `mobile` FROM `accounts` WHERE `mobile` = '$mobile';";
                $res = mysqli_query(connectDB(), $query);
                if($res->num_rows == 1){
                    return true;
                } else {
                    return false;
                }
            }

            function checkEmailPasswordMatching($email, $password) {
                $query = "SELECT `accountId`, `email`, `password` FROM `accounts` WHERE `email` = '$email';";
                $res = mysqli_query(connectDB(), $query);
                while ($row = mysqli_fetch_assoc($res)) {
                    if(password_verify($password, $row["password"])) {
                        //Password matched with email.
                        session_start();
                        $accountId = $row["accountId"];
                        $_SESSION["account"] = $accountId;

                        header('Location: index.php');
                    } else {
                        //Password incorrect.
                        showErrorMessage('Password is incorrect.');
                    }
                }
                if($res->num_rows != 1){
                    //Do not match.
                    showErrorMessage('Email and password do not match.');
                }
            }

            function checkMobilePasswordMatching($mobile, $password) {
                $query = "SELECT `accountId`, `mobile`, `password` FROM `accounts` WHERE `mobile` = '$mobile';";
                $res = mysqli_query(connectDB(), $query);
                while ($row = mysqli_fetch_assoc($res)) {
                    if(password_verify($password, $row["password"])) {
                        //Password matched with mobile.
                        session_start();
                        $accountId = $row["accountId"];
                        $_SESSION["account"] = $accountId;

                        header('Location: index.php');
                    } else {
                        //Password incorrect.
                        showErrorMessage('Password is incorrect.');
                    }
                }
                if($res->num_rows != 1){
                    //Do not match.
                    showErrorMessage('Mobile and password do not match.');
                }
            }

            function showErrorMessage($errormsg) {
                echo '<div class'.'="alert alert-danger" role="alert">'.$errormsg.'</div>';
            };
        ?>

    </div>
</body>
</html>