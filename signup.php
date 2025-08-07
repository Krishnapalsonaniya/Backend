<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username or Email already exists.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $email, $hashed_password);
        if ($insert->execute()) {
            echo "Signup successful! <a href='login.html'>Login here</a>";
        } else {
            echo "Error: " . $insert->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
