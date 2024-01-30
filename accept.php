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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a form with a button to accept the friend request
    if (isset($_POST['accept_request'])) {
        $friend_uid = $_POST['friend_uid'];

        // Update the friendslist table
        $stmt_add_friend = $pdo->prepare("INSERT INTO friendslist (user_id, friend_id) VALUES (?, ?)");
        $stmt_add_friend->execute([$user_id, $friend_uid]);

        // Remove the friend request from the friend_requests table
        $stmt_remove_request = $pdo->prepare("DELETE FROM friend_requests WHERE receiver_id = ? AND requester_id = ?");
        $stmt_remove_request->execute([$user_id, $friend_uid]);

        // Display a success message or perform any other necessary actions
        echo "Friend request accepted successfully!";
    }
}

// Retrieve and display the updated friends list
$stmt_friends = $pdo->prepare("SELECT u.username, u.uid FROM friendslist f JOIN users u ON f.friend_id = u.id WHERE f.user_id = ?");
$stmt_friends->execute([$user_id]);
$friends = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Your Friends List</h2>";
echo "<ul>";
foreach ($friends as $friend) {
    echo "<li>{$friend['username']} ({$friend['uid']})</li>";
}
echo "</ul>";
?>

<!-- Display friend requests (you may have a different page or section for this) -->
<h2>Friend Requests</h2>
<?php
// Retrieve and display friend requests
$stmt_friend_requests = $pdo->prepare("SELECT u.id, u.username, u.uid FROM friend_requests fr JOIN users u ON fr.requester_id = u.id WHERE fr.receiver_id = ?");
$stmt_friend_requests->execute([$user_id]);
$friend_requests = $stmt_friend_requests->fetchAll(PDO::FETCH_ASSOC);

echo "<ul>";
foreach ($friend_requests as $request) {
    echo "<li>{$request['username']} ({$request['uid']}) 
          <form method='post'>
              <input type='hidden' name='friend_uid' value='{$request['id']}'>
              <button type='submit' name='accept_request'>Accept</button>
          </form>
      </li>";
}
echo "</ul>";
?>
