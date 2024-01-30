<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Check if the group_id is provided in the URL
if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    // Fetch the current group information
    $stmt_group = $pdo->prepare("SELECT * FROM groups WHERE id = ?");
    $stmt_group->execute([$group_id]);
    $group = $stmt_group->fetch(PDO::FETCH_ASSOC);

    if (!$group) {
        echo "Error: Group not found.";
        exit;
    }
} else {
    echo "Error: Group ID not provided.";
    exit;
}

// Handle form submission for editing group settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_group_name = $_POST['new_group_name'];
    $new_group_description = $_POST['new_group_description'];

    // Update the group information in the database
    $stmt_update_group = $pdo->prepare("UPDATE groups SET group_name = ?, group_description = ? WHERE id = ?");
    $stmt_update_group->execute([$new_group_name, $new_group_description, $group_id]);

    // Set the success message
    $message = "Changes saved successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Group Settings</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #728FCE, #C34A2C, #342D7E);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            margin: auto;
            margin-top: 20px;
        }

        .notification {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Group Settings</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="new_group_name">New Group Name:</label>
                <input type="text" class="form-control" id="new_group_name" name="new_group_name" value="<?php echo $group['group_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="new_group_description">New Group Description:</label>
                <textarea class="form-control" id="new_group_description" name="new_group_description" required><?php echo $group['group_description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <?php if (isset($message)): ?>
            <div class="alert alert-success notification" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>