<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];

    // Получаем имя друга
    $sql = "SELECT first_name, last_name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $friend_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name);
    $stmt->fetch();
    $stmt->close();

    if (!$first_name || !$last_name) {
        header("Location: friends.php?error=user_not_found");
        exit();
    }

    // Создаем новый чат с именем друга
    $chat_name = "Chat with " . $first_name . " " . $last_name;
    $sql = "INSERT INTO chats (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $chat_name);
    $stmt->execute();
    $chat_id = $stmt->insert_id;
    $stmt->close();

    // Добавляем текущего пользователя и друга в новый чат
    $sql = "INSERT INTO chat_users (chat_id, user_id) VALUES (?, ?), (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $chat_id, $user_id, $chat_id, $friend_id);
    $stmt->execute();
    $stmt->close();

    header("Location: chat.php?id=$chat_id&success=chat_created");
    exit();
}