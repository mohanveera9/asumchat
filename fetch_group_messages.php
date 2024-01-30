<?php
// Include the database configuration file
include 'config.php';

// Get group ID from the request
$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;

if ($group_id) {
    // Fetch and display group chat messages
    $stmt_messages = $pdo->prepare("SELECT m.sender_id, u.username, m.message, m.timestamp FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.group_id = ?");
    $stmt_messages->execute([$group_id]);
    $messages = $stmt_messages->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        echo "<p><strong>{$message['username']}</strong>: {$message['message']} ({$message['timestamp']})</p>";
    }
}
?>
