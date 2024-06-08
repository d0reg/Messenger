<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $phone_number = $_POST['phone_number'];

    // Проверка, существует ли пользователь с указанным номером телефона
    $sql = "SELECT id FROM users WHERE phone_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $stmt->bind_result($friend_id);
    $stmt->fetch();
    $stmt->close();

    if (!$friend_id) {
        // Пользователь не найден
        header("Location: friends.php?error=user_not_found");
        exit();
    }

    // Проверка, не является ли этот пользователь уже другом
    $sql = "SELECT * FROM friends WHERE user_id = ? AND friend_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $friend_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Пользователь уже в списке друзей
        header("Location: friends.php?error=already_friends");
        exit();
    }

    // Добавление пользователя в список друзей
    $sql = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $friend_id);

    if ($stmt->execute()) {
        header("Location: friends.php?success=friend_added");
    } else {
        header("Location: friends.php?error=add_failed");
    }

    $stmt->close();
    $conn->close();
}