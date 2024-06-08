<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$chat_id = $_POST['chat_id'];

if ($_FILES['photo']['error'] == 0) {
    $photo = $_FILES['photo'];
    $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $file_path = 'uploads/' . uniqid() . '.' . $ext;

    if (move_uploaded_file($photo['tmp_name'], $file_path)) {
        $sql = "INSERT INTO messages (chat_id, user_id, message, file_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $chat_id, $user_id, $message = "", $file_path);

        if ($stmt->execute()) {
            header("Location: chat.php?id=$chat_id");
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Failed to move uploaded file.";
    }
} else {
    echo "File upload error.";
}