<?php
// Start the PHP session and include the database connection file
session_start();
include "db_conn.php";

// Check if form data is submitted
if (isset($_POST['First_name']) && isset($_POST['Middle_name']) && 
    isset($_POST['Lastname']) && isset($_POST['Email']) && 
    isset($_POST['password']) && isset($_POST['retypepassword'])) {

    // Function to validate input data
    function validate($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrieve and validate form data
    $First_name = validate($_POST['First_name']);
    $Middle_name = validate($_POST['Middle_name']);
    $Lastname = validate($_POST['Lastname']);
    $Email = validate($_POST['Email']);
    $password = validate($_POST['password']);
    $repassword = validate($_POST['retypepassword']);

    // Check if required fields are empty and handle accordingly
    if (empty($First_name)) {
        header("Location: register-v2.php?error=Firstname is required");
        exit();
    } else if (empty($Middle_name)) {
        header("Location:  register-v2.php?error=Middle name is required");
        exit();
    } else if (empty($Lastname)) {
        header("Location:  register-v2.php?error=Lastname is required");
        exit();
    } else if (empty($Email)) {
        header("Location:  register-v2.php?error=Email is required");
        exit();
    } else if (empty($password)) {
        header("Location:  register-v2.php?error=password is required");
        exit();
    } else if (empty($repassword)) {
        header("Location:  register-v2.php?error=Re password is required");
        exit();
    } else if ($password !== $repassword) {  
        header("Location:  register-v2.php?error=Confirmation password does not match");
        exit();
    } else {
        // Hash the password
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

        // Check if the email already exists
        $check_email_sql = "SELECT * FROM user WHERE Email='$Email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);
        if (mysqli_num_rows($check_email_result) > 0) {
            header("Location: register-v2.php?error=The email is already registered");
            exit();
        } else {
            // Insert user data into the database
            $sql = "INSERT INTO user (First_name, Middle_name, Lastname, Email, password) VALUES ('$First_name', '$Middle_name', '$Lastname', '$Email', '$hashed_pass')";
            $result = mysqli_query($conn, $sql);

            // Redirect to success page or back to registration page with error message
            if ($result) {
                header("Location: login-v2.php");
                exit();
            } else {
                header("Location: register-v2.php?error=Unknown error occurred");
                exit();
            }
        }
    }   
} else {
    // Redirect to the registration page if no form data is submitted
    header("Location: register-v2.php");
    exit();
}
?>
