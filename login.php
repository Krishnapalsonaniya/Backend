<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    if (empty($username_email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username_email, $username_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Incorrect username/email or password.";
        }
    } else {
        echo "User does not exist.";
    }

    $stmt->close();
    $conn->close();
}
?>
