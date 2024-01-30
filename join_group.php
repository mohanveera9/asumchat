<?php
session_start();

// Include the database configuration file
include 'config.php';

// Check if the user is authenticated, redirect to login if not
if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_name = $_POST['group_name'];
    $pin = $_POST['pin'];
    $role = $_POST['role']; // New field

    // Check if the entered group name and PIN are valid
    $stmt_group = $pdo->prepare("SELECT id FROM groups WHERE group_name = ? AND pin = ?");
    $stmt_group->execute([$group_name, $pin]);
    $group = $stmt_group->fetch(PDO::FETCH_ASSOC);

    if ($group) {
        // Check if the user is already a member of the group
        $user_id = $_SESSION['user_id'];
        $stmt_check_member = $pdo->prepare("SELECT * FROM group_members WHERE group_id = ? AND user_id = ?");
        $stmt_check_member->execute([$group['id'], $user_id]);
        $existing_member = $stmt_check_member->fetch(PDO::FETCH_ASSOC);

        if (!$existing_member) {
            // Add the user to the group_members table with the specified role
            $stmt_add_member = $pdo->prepare("INSERT INTO group_members (group_id, user_id, role) VALUES (?, ?, ?)");
            $stmt_add_member->execute([$group['id'], $user_id, $role]);
        }

        // Redirect based on the selected role
        switch ($role) {
            case 'admin':
                header("Location: admin_welcome.php");
                exit;
            case 'invigilator':
                header("Location: invigilator_welcome.php");
                exit;
            case 'member':
                header("Location: group_chat.php?group_id=" . $group['id']);
                exit;
            case 'officer':
                header("Location: officer_welcome.php");
                exit;
            default:
                echo "Invalid role selected.";
                break;
        }
    } else {
        echo "Invalid group name or PIN. Please try again.";
    }
}

// Function to check the number of admins and officers in the group
function isRoleAvailable($role, $group_id, $pdo) {
    $stmt_count_roles = $pdo->prepare("SELECT COUNT(*) AS count FROM group_members WHERE group_id = ? AND role = ?");
    $stmt_count_roles->execute([$group_id, $role]);
    $count = $stmt_count_roles->fetchColumn();
    
    // Limit admins to 2 and officers to 5
    return ($role === 'admin' && $count < 2) || ($role === 'officer' && $count < 5);
}

// Fetch the group ID from the URL or other sources
$group_id = $_GET['group_id'] ?? 0; // Replace 0 with the actual default value

// Fetch the available roles based on conditions
$available_roles = [];
if (isRoleAvailable('admin', $group_id, $pdo)) {
    $available_roles[] = 'Admin';
}
if (isRoleAvailable('officer', $group_id, $pdo)) {
    $available_roles[] = 'Officer';
}
$available_roles[] = 'Invigilator'; // Invigilator is always available
$available_roles[] = 'Member'; // Member is always available
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Group</title>
</head>
<body>
    <h2>Join a Group</h2>
    <form method="post" action="">
        Group Name: <input type="text" name="group_name" required><br>
        Group PIN: <input type="text" name="pin" required><br>
        Role:
        <select name="role" required>
            <?php foreach ($available_roles as $available_role): ?>
                <option value="<?php echo strtolower($available_role); ?>"><?php echo $available_role; ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Join Group</button>
    </form>
</body>
</html>
