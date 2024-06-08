<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$chat_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Проверка, что пользователь в чате
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

// Получение текущего имени чата
$sql = "SELECT name FROM chats WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$stmt->bind_result($chat_name);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=UEFA+Play:wght@400;700&display=swap&subset=cyrillic" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchMessages() {
            $.ajax({
                url: 'fetch_messages.php',
                type: 'GET',
                data: { chat_id: <?php echo $chat_id; ?> },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var messages = response.messages;
                        var messagesContainer = $('.messages');
                        messagesContainer.empty();

                        messages.forEach(function(message) {
                            var messageElement = $('<div class="message"></div>');
                            if (message.profile_photo) {
                                messageElement.append('<img src="' + message.profile_photo + '" alt="Profile Photo" class="profile-photo">');
                            }
                            messageElement.append('<strong>' + message.username + ':</strong>');
                            messageElement.append('<span>' + message.message + '</span>');
                            if (message.file_path) {
                                messageElement.append('<div class="media-file"><img src="' + message.file_path + '" alt="Image" style="max-width: 100%;"></div>');
                            }
                            messageElement.append('<small>' + message.timestamp + '</small>');
                            messagesContainer.append(messageElement);
                        });
                    } else {
                        console.error('Error fetching messages: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch messages: ' + error);
                }
            });
        }

        $(document).ready(function() {
            fetchMessages();
            setInterval(fetchMessages, 2000);
        });
    </script>
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
    <div class="main-container">
        <div class="left-container">
            <h2>Chat Options</h2>
            <form method="post" action="send_message.php" class="form-container">
                <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
                <input type="text" name="message" placeholder="Type your message" required>
                <button type="submit">Send Message</button>
            </form>
            <form method="post" action="send_photo.php" enctype="multipart/form-data" class="form-container">
                <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
                <input type="file" name="photo" accept="image/*" required>
                <button type="submit">Send Photo</button>
            </form>
            <form method="post" action="rename_chat.php" class="form-container">
                    <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
                    <input type="text" name="new_chat_name" placeholder="Enter new chat name" required>
                    <button type="submit">Rename Chat</button>
                </form>
            <form method="post" action="add_user_to_chat.php" class="form-container">
                <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
                <input type="text" name="phone_number" placeholder="Add user by phone number" required>
                <button type="submit">Add User</button>
            </form>
            <form method="post" action="leave_chat.php" class="form-container">
                <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
                <button type="submit">Leave Chat</button>
            </form>
            <a class="btn" href="chats.php">Back to Chats</a>
            <a class="btn" href="profile.php">Go to Profile</a>
        </div>
        <div class="right-container">
            <div class="chat-container">
                <h2><?php echo htmlspecialchars($chat_name); ?></h2>
                <div class="messages"></div>
                
            </div>
        </div>
    </div>
</body>
</html>
