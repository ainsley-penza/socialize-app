<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Socialize</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!--
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
-->
</head>
<body>
    <?php require_once('functions.php'); ?>        

    <div class="p-3 mb-2 bg-primary text-white"><h2>Register for a Socialize account!</h2></div>

    <form id="form" action="register.php" method="POST" class="mx-auto" style="width: 50%;" enctype="multipart/form-data">
        <label class="form-label" for="name">Name:</label>
        <input type="text" class="form-control" placeholder="John" id="name" name="name">

        <label class="form-label" for="surname">Surname:</label>
        <input type="text" class="form-control" placeholder="Doe" id="surname" name="surname">

        <label class="form-label" for="email">Email:</label>
        <input type="text" class="form-control" placeholder="johndoe@gmail.com" id="email" name="email">

        <label class="form-label" for="mobile">Mobile:</label>
        <input type="tel" class="form-control" placeholder="99854837" id="mobile" name="mobile">

        <label class="form-label" for="password">Password:</label>
        <input type="password" class="form-control" placeholder="Password" id="password" name="password">

        <label class="form-label" for="gender">Gender:</label>
        <select class="form-select" name="gender" id="gender">
            <option selected value=''>Select Gender</option>
            <option value="1">Male</option>
            <option value="2">Female</option>
            <option value="3">Other</option>
        </select>

        <label class="form-label" for="country">Country:</label>
        <select class="form-select" name="country" id="countries">
            <option selected value=''>Select Country</option>
        </select>

        <label class="form-label" for="dob">Date of Birth:</label>
        <input type="date" class="form-control" id="dateofbirth" name="dateofbirth">

        
        <label class="form-label" for="picture">Profile Picture:</label>
        <input type="file" class="form-control form-control-lg" id="picture" name="picture">

        <br>
        <button type="submit" class="btn btn-primary btn-lg" name="registerBtn" id="registerBtn">Register</button>
        <br><br>
        <a href="login.php">Already have an account? Login here.</a>
    </form>

    <div id="errors">
        <br><br>

        <?php
            if(isset($_POST['registerBtn'])) {
                $valid = 0;
                //making sure that special characters are omitted from the registration form submissions to prevent SQL errors.
                $name = mysqli_real_escape_string(connectDB(), $_POST['name']);
                $surname = mysqli_real_escape_string(connectDB(), $_POST['surname']);
                $email = mysqli_real_escape_string(connectDB(), $_POST['email']);
                $mobile = mysqli_real_escape_string(connectDB(), $_POST['mobile']);
                $password = mysqli_real_escape_string(connectDB(), $_POST['password']);
                $gender = mysqli_real_escape_string(connectDB(), $_POST['gender']);
                $country = mysqli_real_escape_string(connectDB(), $_POST['country']);
                $dob = mysqli_real_escape_string(connectDB(), $_POST['dateofbirth']);
                $picture = $_FILES['picture'];

                if(empty($name)) {
                    showErrorMessage('Name field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($surname)) {
                    showErrorMessage('Surname field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($email)) {
                    showErrorMessage('Email field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($mobile)) {
                    showErrorMessage('Mobile field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($password)) {
                    showErrorMessage('Password field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($gender)) {
                    showErrorMessage('Gender field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($country)) {
                    showErrorMessage('Country field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($dob)) {
                    showErrorMessage('Date of birth field is empty!');
                }else {
                    ++$valid;
                }
                if(empty($picture)) {
                    showErrorMessage('Picture field is empty!');
                }else {
                    ++$valid;
                }
                //checking for 3+ character long passwords
                if(strlen($password) < 3 && !empty($password)) {
                    showErrorMessage('Password should be at least 3 characters long!');
                }else {
                    ++$valid;
                }

                //checking for email matching regex.
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
                    showErrorMessage('Email is not in the correct format!');
                }else {
                    ++$valid;
                }

                //only allow letters in name and surname
                if ((!preg_match("/^[a-zA-Z-' ]*$/", $name) && !empty($name)) || strlen($name) > 15) {
                    showErrorMessage('Name should only contain letters and cannot contain more than 15 characters.');
                }else {
                    ++$valid;
                }

                if ((!preg_match("/^[a-zA-Z-' ]*$/",$surname) && !empty($surname)) || strlen($surname) > 15) {
                    showErrorMessage('Surname should only contain letters and cannot contain more than 15 characters.');
                }else {
                    ++$valid;
                }

                if(checkForDuplicateEmail($email)) {
                    showErrorMessage('Email already exists. Please try again.');
                }else {
                    ++$valid;
                }

                if(checkForDuplicateMobile($mobile)) {
                    showErrorMessage('Mobile already exists. Please try again.');
                }else {
                    ++$valid;
                }

                if($valid == 15) {
                    //add user to database.
                    //trim the fields to make sure that there are no extra whitespace characters at the beginning and end of the fields.
                    $uploaddir = 'img/profiles/';
                    $uploadfile = $uploaddir . basename($_FILES['picture']['name']);

                    move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile);

                    registerUser(trim($name), trim($surname), trim($email), trim($mobile), password_hash(trim($password), PASSWORD_DEFAULT), trim($gender), trim($country), trim($dob), basename($_FILES['picture']['name']), connectDB());
                    header('Location: login.php');
                }

            }

            function showErrorMessage($errormsg) {
                echo '<div class'.'="alert alert-danger" role="alert">'.$errormsg.'</div>';
            };

            function checkForDuplicateEmail($email) {
                $query = "SELECT `email` FROM `accounts` WHERE `email` = '$email';";
                $res = mysqli_query(connectDB(),$query);
                if($res->num_rows != 0){
                    return true;
                } else {
                    return false;
                }
            }

            function checkForDuplicateMobile($mobile) {
                $query = "SELECT `mobile` FROM `accounts` WHERE `mobile` = '$mobile';";
                $res = mysqli_query(connectDB(),$query);
                if($res->num_rows != 0){
                    return true;
                } else {
                    return false;
                }
            }
        ?>
    </div>

<script src="js/register.js"></script>
</body>
</html>