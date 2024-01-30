<?php
// Include necessary files and start the session
error_reporting(E_ERROR | E_PARSE);
session_start();
include 'config.php';

// Check if the user is authenticated, redirect to login if not
if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}

// Get user details from the database
$user_id = $_SESSION["user_id"];
$stmt_user = $pdo->prepare("SELECT username, uid, email FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Get user's friends list
$stmt_friends = $pdo->prepare("SELECT friend_name FROM friends WHERE user_id = ?");
$stmt_friends->execute([$user_id]);
$friends = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);

// Get user's group memberships
$stmt_groups = $pdo->prepare("SELECT g.group_name FROM group_members gm JOIN groups g ON gm.group_id = g.id WHERE gm.user_id = ?");
$stmt_groups->execute([$user_id]);
$groups = $stmt_groups->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Profile</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #728FCE, #C34A2C, #342D7E);
            color: #fff;
        }

        .container {
            max-width: 700px; /* Decreased width */
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #007bff;
            margin-top: 20px;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 5px;
            color: #000; /* Black color */
        }

        p {
            margin-bottom: 10px;
            color: #000; /* Black color */
        }

        /* Custom style to change font color to black and increase font size and padding */
        .user-info {
            color: #000; /* Black color */
            font-size: 18px; /* Increased font size */
            padding: 8px 0; /* Increased padding */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>User Profile</h2>
    <div class="row">
        <div class="col-md-6">
            <p class="user-info"><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p class="user-info"><strong>UID:</strong> <?php echo $user['uid']; ?></p>
            <p class="user-info"><strong>Email:</strong> <?php echo $user['email']; ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Group Memberships</h3>
            <ul>
                <?php foreach ($groups as $group): ?>
                    <li><?php echo $group['group_name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h3>Friends List</h3>
            <ul>
                <?php foreach ($friends as $friend): ?>
                    <li><?php echo $friend['friend_name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>