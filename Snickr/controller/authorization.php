<?php
require_once 'email.php';
session_start();
$username = "";
$email = "";
$errors = [];

$conn = new mysqli('localhost', 'root', '', 'snicker');
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// SIGN UP USER
if (isset($_POST['signup-btn'])) {
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username required';
    }
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email required';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password required';
    }
    if (isset($_POST['password']) && $_POST['password'] !== $_POST['passwordConf']) {
        $errors['passwordConf'] = 'The two passwords do not match';
    }

    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $token = bin2hex(random_bytes(50)); // generate unique token
    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT); //encrypt password

    // Check if email already exists
    $sql = "SELECT * FROM user WHERE uemail='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $errors['email'] = "Email already exists";
    }

    if (count($errors) === 0) {
        $query = "INSERT INTO user SET uname=?, uemail=?, utoken=?, upassword=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $username, $email, $token, $password);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // send verification email to user
            sendVerificationEmail($email, $token);

            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = false;
            $_SESSION['message'] = 'You are logged in!';
            $_SESSION['type'] = 'alert-success';
            header('location: index.php');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}

// LOGIN
if (isset($_POST['login-btn'])) {
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email required';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password required';
    }
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $query = "SELECT * FROM user WHERE uemail=? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) == 0) {
            $errors['email'] = "No account found with this email";
        }
        if (count($errors) === 0) {
            $user = $result->fetch_assoc();
            //if (password_verify($password, $user['upassword'])) { // if password matches
            if ($password == $user['upassword'] || password_verify($password, $user['upassword'])) { 
                $stmt->close();
                $_SESSION['id'] = $user['uid'];
                $_SESSION['username'] = $user['uname'];
                $_SESSION['email'] = $user['uemail'];
                $_SESSION['verified'] = $user['uverified'];
                $_SESSION['message'] = 'You are logged in!';
                $_SESSION['type'] = 'alert-success';
                
                if(isset($_SESSION['redirect_url'])){
                    $redirect_url = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']);
                    header("Location: $redirect_url", true, 303);        
                }
                else     
                header('location: index.php');
                exit(0);
            } else { // if password does not match
                $errors['login_fail'] = "Wrong email / password";
            }
        } 
    }
    else {
        $_SESSION['message'] = "Database error. Login failed!";
        $_SESSION['type'] = "alert-danger";
    }
}