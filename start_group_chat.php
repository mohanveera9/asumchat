<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Function to generate a 4-digit PIN
function generatePIN() {
    return sprintf('%04d', mt_rand(0, 9999));
}

$pin = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_name = $_POST['group_name'];
    $group_description = $_POST['group_description'];

    // Generate a 4-digit random PIN
    $pin = generatePIN();

    // Insert the group into the database along with the PIN
    $stmt = $pdo->prepare("INSERT INTO groups (group_name, group_description, pin) VALUES (?, ?, ?)");
    $stmt->execute([$group_name, $group_description, $pin]);
    $group_id = $pdo->lastInsertId();

    // Assign the user as an admin for the group
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO group_members (group_id, user_id, role) VALUES (?, ?, 'admin')");
    $stmt->execute([$group_id, $user_id]);

    // Redirect to group management along with the group ID
    header("Location: group_management.php?group_id=$group_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Group</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Create a Group</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="group_name">Group Name:</label>
                <input type="text" class="form-control" id="group_name" name="group_name" required>
            </div>
            <div class="form-group">
                <label for="group_description">Group Description:</label>
                <textarea class="form-control" id="group_description" name="group_description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Generate PIN and Create Group</button>
        </form>

        <!-- Display the generated PIN only if it exists -->
        <?php if ($pin): ?>
            <div class="mt-3">
                <p class="alert alert-success">Generated PIN: <strong><?php echo $pin; ?></strong></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

