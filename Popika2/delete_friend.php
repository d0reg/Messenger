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

    // Удаление друга из списка друзей
    $sql = "DELETE FROM friends WHERE user_id = ? AND friend_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $user_id, $friend_id);
        if ($stmt->execute()) {
            header("Location: friends.php?success=friend_deleted");
        } else {
            header("Location: friends.php?error=delete_failed");
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    $conn->close();
}