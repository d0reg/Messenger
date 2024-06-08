<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$chat_id = $_GET['chat_id'];
$user_id = $_SESSION['user_id'];

// Проверка, что пользователь в чате
$sql = "SELECT * FROM chat_users WHERE chat_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $chat_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Not a member of this chat']);
    exit();
}

// Получаем сообщения чата
$sql = "SELECT messages.message, messages.timestamp, messages.file_path, users.username, users.profile_photo 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        WHERE messages.chat_id = ? 
        ORDER BY messages.timestamp";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'messages' => $messages]);