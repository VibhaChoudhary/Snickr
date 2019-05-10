<?php
include 'authorization.php';
include 'dbqueries.php';

$conn = new mysqli('localhost', 'root', '', 'snicker');

if (isset($_GET['token']) && !empty($_GET['token'])) {
    //echo $_GET['token'];
    $token = htmlspecialchars($_GET['token']);
    $sql = "SELECT * FROM user WHERE utoken='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $query = "UPDATE user SET uverified=1 WHERE utoken='$token'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['uname'];
            $_SESSION['email'] = $user['uemail'];
            $_SESSION['verified'] = true;
            $_SESSION['message'] = "Your email address has been verified successfully";
            $_SESSION['type'] = 'alert-success';
            header('location: index.php');
            exit(0);
        }
    }
    else {
        echo "User not found!";
    }
}
else if(empty($_GET['token'])){
    echo "No token provided!";
}
else{
     echo "No token provided!";
}
?>
