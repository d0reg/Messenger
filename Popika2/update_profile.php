<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];

    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $file_tmp = $_FILES['profile_photo']['tmp_name'];
        $file_name = $_FILES['profile_photo']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $profile_photo = 'uploads/' . uniqid() . '.' . $file_ext;
        move_uploaded_file($file_tmp, $profile_photo);
    }

    if ($profile_photo) {
        $sql = "UPDATE users SET username = ?, first_name = ?, last_name = ?, phone_number = ?, profile_photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisi", $username, $first_name, $last_name, $phone_number, $profile_photo, $user_id);
    } else {
        $sql = "UPDATE users SET username = ?, first_name = ?, last_name = ?, phone_number = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $username, $first_name, $last_name, $phone_number, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: profile.php?success=1");
    } else {
        header("Location: profile.php?error=1");
    }

    $stmt->close();
    $conn->close();
}