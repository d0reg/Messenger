<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение списка друзей
$sql = "SELECT u.id, u.username, u.first_name, u.last_name, u.profile_photo 
        FROM friends f 
        JOIN users u ON f.friend_id = u.id 
        WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Friends</title>
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
    <div class="friends-container">
        <h2>Friends</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php 
                if ($_GET['error'] == 'chat_exists') {
                    echo "Chat with this friend already exists.";
                } else {
                    echo "An error occurred.";
                }
                ?>
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="success">
                <?php 
                if ($_GET['success'] == 'chat_created') {
                    echo "Chat created successfully!";
                }
                ?>
            </div>
        <?php endif; ?>
        <ul>
            <?php foreach ($friends as $friend): ?>
                <li>
                    <?php if ($friend['profile_photo']): ?>
                        <img src="<?php echo $friend['profile_photo']; ?>" alt="Profile Photo" class="profile-photo-small">
                    <?php endif; ?>
                    <?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?> (<?php echo htmlspecialchars($friend['username']); ?>)
                    <form method="post" action="delete_friend.php" style="display:inline;">
                        <input type="hidden" name="friend_id" value="<?php echo $friend['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form method="post" action="create_chat_with_friend.php" style="display:inline;">
                        <input type="hidden" name="friend_id" value="<?php echo $friend['id']; ?>">
                        <button type="submit">Create Chat</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="post" action="add_friend.php" class="form-container">
            <input type="text" name="phone_number" placeholder="Add friend by phone number" required>
            <button type="submit">Add Friend</button>
        </form>
        <a class="btn" href="chats.php">Back to Chats</a>
        <a class="btn" href="profile.php">Go to Profile</a>
    </div>
</body>
</html>
