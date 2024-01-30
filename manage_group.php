]<?php
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
$stmt_group = $pdo->prepare("SELECT * FROM groups WHERE id = ?");
$stmt_group->execute([$group_id]);
$group = $stmt_group->fetch(PDO::FETCH_ASSOC);

// Get user role in the group
$stmt_membership = $pdo->prepare("SELECT role FROM group_members WHERE user_id = ? AND group_id = ?");
$stmt_membership->execute([$user_id, $group_id]);
$membership = $stmt_membership->fetch(PDO::FETCH_ASSOC);
$user_role = $membership['role'] ?? 'member';

// Ensure that the user is an admin
if ($user_role !== 'admin') {
    echo "You do not have permission to access this page.";
    exit;
}

// Handle form submissions or other group management logic here
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submissions or other logic
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Manage Group: <?php echo htmlspecialchars($group['group_name']); ?></h2>

    <!-- Include various options for group management here -->
    <p><a href='change_group_name.php?group_id=<?php echo $group_id; ?>'>Change Group Name</a></p>
    <p><a href='change_group_settings.php?group_id=<?php echo $group_id; ?>'>Change Group Settings</a></p>
    <p><a href='remove_members.php?group_id=<?php echo $group_id; ?>'>Remove Members</a></p>
    <!-- Add more options based on your requirements -->

    <h3>Back to <a href="dashboard.php">Dashboard</a></h3>
</body>
</html>
