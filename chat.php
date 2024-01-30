<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AsumChat - Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Your custom style.css file -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" >

</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Friends List Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light " style="background: linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5); ">
               
        <div class="sidebar-sticky">
        <h4 class="heading2">
                    Your Friends
                </h4>
                <div>
                <a href="send_request.php" class="btn btn-success " style="margin-left:150px;  ">
                                        <i class="fa fa-user-plus"></i>
                                     </a>
                
                </div>
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

echo "<h2></h2>";
echo "<ul class='nav flex-column'>";
foreach ($friends as $friend) {
    echo "<li class='nav-item'>";
    // Add style attribute to change text color to black
    echo "<a class='nav-link' href='#' style='color: black;'>{$friend['username']}</a>";
    echo "</li>";
}
echo "</ul>";
?>

            </div>
        </nav>


        

        <!-- Chat Interface -->
            <?php if (count($friends) > 0): ?>
					<div class="card">
						<div class="card-header msg_head">
							<div class="d-flex bd-highlight">
                            <div class="img_cont">
									<img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
									<span class="online_icon"></span>
								</div>
								<div class="user_info">
									<span>ASUM CHAT</span>
									
								</div>
                                <div class="video_cam">
								
                                      <a href="dashboard.php" class="btn btn-success " style="margin-left:750px;  ">
                                      <i class="fas fa-home"></i>   
                                     </a>
                                  
                                   
        
								</div>
                              
							
							</div>
                            
							<span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
							<div class="action_menu">
								<ul>
									<li><i class="fas fa-user-circle"></i> View profile</li>
									<li><i class="fas fa-users"></i> Add to close friends</li>
									<li><i class="fas fa-plus"></i> Add to group</li>
									<li><i class="fas fa-ban"></i> Block</li>
								</ul>
							</div>
						</div>
						<div class="card-body msg_card_body">
													
						</div>
						<div class="card-footer">
							<div class="input-group">
								<div class="input-group-append">
									<span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
								</div>
								<textarea name="" class="form-control type_msg" placeholder="Type your message..."></textarea>
								<div class="input-group-append">
									<span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
								</div>
							</div>
						</div>
					</div>
				
               
						
				<?php
                error_reporting(E_ERROR | E_PARSE);
                    $stmt_messages = $pdo->prepare("SELECT m.sender_id, u.username, m.message, m.timestamp FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.group_id = ?");
                    $stmt_messages->execute([$group_id]); // Modify group_id accordingly
                    $messages = $stmt_messages->fetchAll(PDO::FETCH_ASSOC);

                    
                    foreach ($messages as $message) {
                        echo "<div><strong>{$message['username']}:</strong> {$message['message']} ({$message['timestamp']})</div>";
                    }
                    ?>		
                    <?php
error_reporting(E_ERROR | E_PARSE);
?>

            <?php else: ?>
                
            <?php endif; ?>
        

        <!-- ... (remaining code) ... -->

    </div>
</div>


<!-- Include the Bootstrap JS scripts at the end of your HTML file -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
	$('#action_menu_btn').click(function(){
		$('.action_menu').toggle();
	});
});
    </script>

</body>
</html>