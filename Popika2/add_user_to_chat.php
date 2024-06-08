<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chat_id = $_POST['chat_id'];
    $phone_number = $_POST['phone_number'];

    // Получаем пользователя по номеру телефона
    $sql = "SELECT id FROM users WHERE phone_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        // Добавляем пользователя в чат
        $sql = "INSERT INTO chat_users (chat_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $chat_id, $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: chat.php?id=$chat_id");
    } else {
        echo "User not found.";
    }
}