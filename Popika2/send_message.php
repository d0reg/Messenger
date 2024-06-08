<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO messages (chat_id, user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $chat_id, $user_id, $message);
    $stmt->execute();
    $stmt->close();

    header("Location: chat.php?id=$chat_id");
}