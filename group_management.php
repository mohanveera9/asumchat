<?php
session_start();

// Check if the user is authenticated, redirect to login if not
if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}

// Include the database configuration file
include 'config.php';

// Get user details from the database
$user_id = $_SESSION["user_id"];
$stmt_user = $pdo->prepare("SELECT username, uid FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Get group details
$group_id = $_GET['group_id']; // Assuming the group_id is passed in the URL

// Fetch the PIN from the database
$stmt_pin = $pdo->prepare("SELECT pin FROM groups WHERE id = ?");
$stmt_pin->execute([$group_id]);
$pin = $stmt_pin->fetchColumn();

// If the group does not exist or does not have a PIN, redirect
if (!$pin) {
    header("Location: index.html");
    exit;
}

// Get user role in the group
$stmt_membership = $pdo->prepare("SELECT role FROM group_members WHERE user_id = ? AND group_id = ?");
$stmt_membership->execute([$user_id, $group_id]);
$membership = $stmt_membership->fetch(PDO::FETCH_ASSOC);
$user_role = $membership['role'] ?? 'member';

// Display group-specific information
$stmt_group = $pdo->prepare("SELECT group_name, group_description FROM groups WHERE id = ?");
$stmt_group->execute([$group_id]);
$group = $stmt_group->fetch(PDO::FETCH_ASSOC);

if ($group) {
    echo "<h2>{$group['group_name']} Group Management</h2>";
    echo "<p>Description: {$group['group_description']}</p>";
    
    // Fetch and display group members
    echo "<p>Members: ";
    $stmt_group_members = $pdo->prepare("SELECT u.username FROM group_members gm JOIN users u ON gm.user_id = u.id WHERE gm.group_id = ?");
    $stmt_group_members->execute([$group_id]);
    $members = $stmt_group_members->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($members as $member) {
        echo "{$member['username']}, ";
    }
    echo "</p>";
} else {
    echo "Group not found.";
}


// Display the generated PIN
echo "<p>Generated PIN: <strong>{$pin}</strong></p>";

// Common actions for all roles
echo "<p>Common actions for all roles:</p>";
echo "<ul>";
echo "<li><a href='join_group.php?group_id={$group_id}'>Go to Group Chat</a></li>";
echo "<li><a href='edit_settings.php?group_id={$group_id}'>Edit Settings</a></li>";
echo "</ul>";

?>
