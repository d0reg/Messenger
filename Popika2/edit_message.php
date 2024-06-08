<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$message_id = $_POST['message_id'];
$new_message = $_POST['message'];

// Проверка, что пользователь является автором сообщения
$sql = "SELECT user_id FROM messages WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Message not found']);
    exit();
}

$row = $result->fetch_assoc();
if ($row['user_id'] != $user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not authorized']);
    exit();
}

$stmt->close();

// Обновление сообщения
$sql = "UPDATE messages SET message = ?, edited = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_message, $message_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update message']);
}

$stmt->close();