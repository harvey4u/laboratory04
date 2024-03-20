<?php
// Start the PHP session and include the database connection file
session_start();
include "db_conn.php";

// Check if form data is submitted
if (isset($_POST['Pnum']) && isset($_POST['month']) && 
    isset($_POST['day']) && isset($_POST['year']) && 
    isset($_POST['male']) && isset($_POST['female']) && 
    isset($_POST['province']) && isset($_POST['city']) && 
    isset($_POST['barangay']) && isset($_POST['zip_code'])){

    // Function to validate input data
    function validate($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrieve and validate form data
    $pnum = validate($_POST['Pnum']);
    $month = validate($_POST['month']);
    $day = validate($_POST['day']);
    $year = validate($_POST['year']);
    $male = validate($_POST['male']);
    $female = validate($_POST['female']);
    $province = validate($_POST['province']);
    $city = validate($_POST['city']);
    $barangay = validate($_POST['barangay']);
    $zip_code = validate($_POST['zip_code']);


    

    // Check if required fields are empty and handle accordingly
    if (empty($Pnum)) {
        header("Location: register-v2.php?error=Firstname is required");
        exit();
    }else if (empty($month)){
        header("Location:  register-v2.php?error=Middle name is required");
        exit();
    }else if (empty($day)){
        header("Location:  register-v2.php?error=Lastname is required");
        exit();
    }else if (empty($year)){
        header("Location:  register-v2.php?error=Email is required");
        exit();
    }else if (empty($male) && empty($female)){
        header("Location:  register-v2.php?error=Password is required");
        exit();
    }else if (empty($province)){
        header("Location:  register-v2.php?error=Re password is required");
        exit();
    }else if ($barangay) {  
        header("Location:  register-v2.php?error=Confirmation password is not match ");
        exit();
    }else if ($zip_code) {  
        header("Location:  register-v2.php?error=Confirmation password is not match ");
        exit();
    }else {
        // Hash the password
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

        // Check if the username already exists
        $sql = "SELECT * FROM user WHERE username='$username'";
        $result = mysqli_query($conn, $sql);

        // If username exists, redirect with error message
        if (mysqli_num_rows($result) > 0) {
            header("Location: register.php?error=The username is already taken ");
            exit();
            }else{
                // Insert user data into the database
                $sql2 =  "INSERT INTO user (First_name, Middle_name, Lastname, Email, password) VALUES ('$First_name', '$Middle_name', '$Lastname', '$Email', '$hashed_pass')";

                // Redirect to success page or back to registration page with error message
            }if ($result2) {
                header("Location: profile.php");
                exit();
            }else{
                header("Location:  register-v2.php?error=Unknown error occured");
        exit();
            }
        }   
}else {
    // Redirect to the registration page if no form data is submitted
    header("Location:  register-v2.php");
    exit();
}