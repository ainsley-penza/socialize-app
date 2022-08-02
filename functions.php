<?php
    function connectDB() {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $database = "socialize";

        //creating a connection to MySQL socialize database.
        $connection = new mysqli($servername, $username, $password, $database);

        if(!$connection) {
            die("Connection to the database failed. " . mysqli_connect_error());
        }
        
        return $connection;
    }

    function registerUser($name, $surname, $email, $mobile, $password, $gender, $country, $dob, $image, $connection) {
        $sql = "INSERT INTO `accounts` (`accountId`, `email`, `mobile`, `password`, `name`, `surname`, `genderId`, `countryId`, `dateOfBirth`, `image`) ";
        $sql .= "VALUES (NULL, '$email', '$mobile', '$password', '$name', '$surname', '$gender', '$country', '$dob', '$image');";

        if(mysqli_query($connection, $sql)) {
            echo "$name $surname was added to the database.";
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        //close the connection to secure updates.
        mysqli_close($connection);
    }

    //function addCountry($country, $connection) {
    //    $sql = "INSERT INTO `countries` (`countryId`, `name`) ";
    //    $sql .= "VALUES (NULL, '$country');";
//
    //    if(mysqli_query($connection, $sql)) {
    //        echo $country . ' was added.';
    //    } else {
    //        echo "Error: " . mysqli_error($connection);
    //    }
//
    //    //close the connection to secure updates.
    //    mysqli_close($connection);
    //}
?>