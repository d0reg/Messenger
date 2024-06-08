<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chat_name = $_POST['chat_name'];
    $user_id = $_SESSION['user_id'];

    // Создаем новый чат
    $sql = "INSERT INTO chats (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $chat_name);
    $stmt->execute();
    $chat_id = $stmt->insert_id;
    $stmt->close();

    // Добавляем создателя в чат
    $sql = "INSERT INTO chat_users (chat_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $chat_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: chat.php?id=$chat_id");
}