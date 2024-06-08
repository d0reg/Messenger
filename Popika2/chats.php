<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение списка чатов
$sql = "SELECT chats.id, chats.name FROM chats 
        JOIN chat_users ON chats.id = chat_users.chat_id 
        WHERE chat_users.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$chats = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>
    <div class="chats-container">
        <h2>Chats</h2>
        <div class="chat-list">
            <ul>
                <?php foreach ($chats as $chat): ?>
                    <li>
                        <a href="chat.php?id=<?php echo $chat['id']; ?>"><?php echo htmlspecialchars($chat['name']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <form method="post" action="create_chat.php">
            <input type="text" name="chat_name" placeholder="New Chat Name" required>
            <button type="submit">Create Chat</button>
        </form>
        <div class="btn-group">
            <a class="btn" href="friends.php">Friends</a>
            <a class="btn" href="profile.php">Go to Profile</a>
            <a class="btn" href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
