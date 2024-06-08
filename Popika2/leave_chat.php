<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $chat_id = $_POST['chat_id'];

    // Удаление пользователя из чата
    $sql = "DELETE FROM chat_users WHERE chat_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $chat_id, $user_id);

    if ($stmt->execute()) {
        header("Location: chats.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}