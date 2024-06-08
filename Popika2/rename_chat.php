<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chat_id = $_POST['chat_id'];
    $new_chat_name = $_POST['new_chat_name'];
    $user_id = $_SESSION['user_id'];

    // Проверка, что пользователь является участником чата
    $sql = "SELECT * FROM chat_users WHERE chat_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $chat_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "You are not a member of this chat.";
        exit();
    }

    $stmt->close();

    // Обновление имени чата
    $sql = "UPDATE chats SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_chat_name, $chat_id);
    $stmt->execute();
    $stmt->close();

    header("Location: chat.php?id=$chat_id&success=chat_renamed");
    exit();
}