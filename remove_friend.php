<?php
session_start();
include 'config.php';

// Check if the user is authenticated, redirect to login if not
if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}

// Get user details from the database
$user_id = $_SESSION["user_id"];
$stmt_user = $pdo->prepare("SELECT username, uid FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Initialize the message variable
$message = "";

// Check if the form is submitted for removing a friend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_friend'])) {
    $friend_id = $_POST['friend_id'];

    // Remove the friend from the friendslist table
    $stmt_remove_friend = $pdo->prepare("DELETE FROM friendslist WHERE user_id = ? AND friend_id = ?");
    $stmt_remove_friend->execute([$user_id, $friend_id]);

    // Set the success message
    $message = "Friend removed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Friends List - WhatsApp Style</title>
    <!-- Bootstrap CSS - using a WhatsApp-style theme -->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/materia/bootstrap.min.css" rel="stylesheet">
    <!-- Your Custom CSS -->

    <style>
        body {
            background: linear-gradient(to right, #728FCE, #C34A2C, #342D7E);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: black;
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

        .friends-list {
            list-style-type: none;
            padding: 0;
        }

        .friends-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #ffffff; /* White background */
            border-radius: 8px;
        }

        .remove-friend-form {
            margin-top: 10px;
        }

        .notification {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Friends List</h2>
        <ul class="friends-list">
            <?php
            $stmt_friends = $pdo->prepare("SELECT u.id, u.username, u.uid FROM friendslist f JOIN users u ON f.friend_id = u.id WHERE f.user_id = ?");
            $stmt_friends->execute([$user_id]);
            $friends = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);

            foreach ($friends as $friend) {
                echo "<li>{$friend['username']} ({$friend['uid']})";
                echo "<div class='remove-friend-form'><form method='post' action=''>";
                echo "<input type='hidden' name='friend_id' value='{$friend['id']}'>";
                echo "<button type='submit' name='remove_friend' class='btn btn-danger'>Remove Friend</button>";
                echo "</form></div></li>";
            }
            ?>
        </ul>

        <?php if ($message): ?>
            <div class="alert alert-success notification" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>