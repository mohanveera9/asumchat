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

// Check if the form is submitted for adding a friend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_friend'])) {
    $friend_uid = $_POST['friend_uid'];

    // Check if the friend UID exists in the users table
    $stmt_check_friend = $pdo->prepare("SELECT id FROM users WHERE uid = ?");
    $stmt_check_friend->execute([$friend_uid]);
    $friend_exists = $stmt_check_friend->fetch();

    if ($friend_exists) {
        // Add the friend to the friendslist table
        $stmt_add_friend = $pdo->prepare("INSERT INTO friendslist (user_id, friend_id) VALUES (?, ?)");
        $stmt_add_friend->execute([$user_id, $friend_exists['id']]);

        // Set the success message
        $message = "Friend added successfully!";
    } else {
        $message = "Invalid friend UID. Please try again.";
    }
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

        .add-friend-form {
            background-color: #ffffff; /* White background for the form */
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Friends List</h2>
        <ul class="friends-list">
            <?php
            $stmt_friends = $pdo->prepare("SELECT u.username, u.uid FROM friendslist f JOIN users u ON f.friend_id = u.id WHERE f.user_id = ?");
            $stmt_friends->execute([$user_id]);
            $friends = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);

            foreach ($friends as $friend) {
                echo "<li>{$friend['username']} ({$friend['uid']})</li>";
            }
            ?>
        </ul>

        <div class="add-friend-form">
            <h2 style="color: black;">Add a Friend</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="friend_uid" style="color: black;"><h5>Friend UID:</h5></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="friend_uid" required>
                        <div class="input-group-append">
                            <button type="submit" name="add_friend" class="btn btn-primary">Add Friend</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>