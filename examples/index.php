<?php
session_start();
include "db_conn.php";

if (isset($_POST['Email']) && isset($_POST['password'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $Email = validate($_POST['Email']);
    $password = validate($_POST['password']);

    if (empty($Email) || empty($password)) {
        header("Location: login-v2.php?error=Email and password are required");
        exit();
    } else {
        $sql = "SELECT * FROM user WHERE Email=?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $Email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                // Verify the password using password_verify
                if (password_verify($password, $row["password"])) {
                    $_SESSION['Email'] = $row['Email'];
                    header("Location: profile.php");
                    exit();
                } else {
                    header("Location: login-v2.php?error=Incorrect password");
                    exit();
                }
            } else {
                header("Location: login-v2.php?error=User not found");
                exit();
            }
        } else {
            header("Location: login-v2.php?error=Database error. Please try again later.");
            exit();
        }
    }
} else {
    header("Location: login-v2.php");
    exit();
}
?>
