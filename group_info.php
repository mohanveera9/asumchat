<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$group_id = $_GET['group_id']; // Make sure to handle this securely
$stmt_group = $pdo->prepare("SELECT * FROM groups WHERE id = ?");
$stmt_group->execute([$group_id]);

// Check if the query was successful
$group = $stmt_group->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    // Redirect or display an error message, as appropriate
    echo "Error: Group not found.";
    exit;
}
$stmt_group->execute([$group_id]);

if ($stmt_group->errorCode() !== '00000') {$stmt_group->execute([$group_id]);

    if ($stmt_group->errorCode() !== '00000') {
        $errorInfo = $stmt_group->errorInfo();
        echo "Database Error: " . $errorInfo[2];
        exit;
    }
    
    $group = $stmt_group->fetch(PDO::FETCH_ASSOC);
    
    $errorInfo = $stmt_group->errorInfo();
    echo "Database Error: " . $errorInfo[2];
    exit;
}

$group = $stmt_group->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Group Information</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Group Information</h2>
        <p><strong>Group Name:</strong> <?php echo $group['group_name']; ?></p>
        <p><strong>Group Description:</strong> <?php echo $group['group_description']; ?></p>

        <!-- Add more details as needed -->
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
