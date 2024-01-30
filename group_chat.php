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
$stmt_user = $pdo->prepare("SELECT username, uid FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Check if a group is selected
$selectedGroupName = isset($_POST['group_name']) ? htmlspecialchars($_POST['group_name']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AsumChat - Group Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            background: #f0f0f0;
        }

        .container-fluid {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            background: #fff;
            flex: 1;
            overflow: hidden;
        }

        #chat-box {
            height: 70vh;
            overflow-y: auto;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        #message-form {
            display: flex;
            padding: 20px;
            border-top: 1px solid #ddd;
        }

        textarea {
            resize: none;
            flex-grow: 1;
            margin-right: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            height: 40px;
            align-self: flex-end;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Groups List Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <?php
                // Fetch and display the user's groups list
                $stmt_groups = $pdo->prepare("SELECT g.id, g.group_name, g.group_description FROM group_members gm JOIN groups g ON gm.group_id = g.id WHERE gm.user_id = ?");
                $stmt_groups->execute([$user_id]);
                $groups = $stmt_groups->fetchAll(PDO::FETCH_ASSOC);

                echo "<h4 class='sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted'>Your Groups</h4>";
                echo "<form method='post'>";
                echo "<ul class='nav flex-column'>";
                foreach ($groups as $group) {
                    echo "<li class='nav-item'>";
                    echo "<button type='submit' class='nav-link group-link' name='group_name' value='{$group['group_name']}'>{$group['group_name']}</button>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</form>";
                ?>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <?php if ($selectedGroupName): ?>
                    <h1 class="h2"><?php echo $selectedGroupName; ?></h1>
                    <!-- Button with Dropdown -->
                    <div class="btn-group ml-auto mt-2" role="group" aria-label="Options">
                        <button type="button" class="btn btn-outline-primary" onclick="handleOption('blur')">Blur Chat</button>
                        <button type="button" class="btn btn-outline-primary" onclick="handleOption('hide')">Hide Chat</button>
                        <button type="button" class="btn btn-outline-primary" onclick="handleOption('busy')">Busy Mode</button>
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#groupInfoModal">Group Info</button>
                    </div>
                <?php else: ?>
                    <h1 class="h2">AsumChat</h1>
                <?php endif; ?>
            </div>

            <div id='chat-box'>
                <!-- Chat messages will be displayed here -->
                <div class="message received">Hello, how are you?</div>
                <div class="message sent">I'm doing well, thanks!</div>
                <div class="message received">That's great to hear.</div>
            </div>

            <form id='message-form' class="mt-3">
                <textarea name='message' placeholder='Type your message...' class="form-control"></textarea>
                <button type='submit' class="btn btn-primary">Send</button>
            </form>
        </main>
    </div>
</div>
<div class="modal fade" id="groupInfoModal" tabindex="-1" role="dialog" aria-labelledby="groupInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupInfoModalLabel">Group Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display group information here -->
                <?php
// Fetch and display group details
$stmt_group_info = $pdo->prepare("SELECT group_name, group_description
                                 FROM groups
                                 WHERE group_name = ?");

$stmt_group_info->execute([$selectedGroupName]);
$groupInfo = $stmt_group_info->fetch(PDO::FETCH_ASSOC);

echo "<p><strong>Group Name:</strong> {$groupInfo['group_name']}</p>";
echo "<p><strong>Description:</strong> {$groupInfo['group_description']}</p>";
?>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var groupInfo = document.getElementById('group-info');
        var chatBox = document.getElementById('chat-box');
        var messageForm = document.getElementById('message-form');

        // Add submit event listener to the message form
        messageForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var messageInput = document.querySelector('textarea[name="message"]');
            var message = messageInput.value;

            // Get the current active group's ID from the clicked group link
            var activeGroupLink = document.querySelector('.group-link.active');
            var groupId = activeGroupLink ? activeGroupLink.getAttribute('data-group-id') : null;

            if (groupId && message.trim() !== '') {
                // Send the message to the server and update the chat box
                sendMessageToGroup(groupId, message);
            }

            // Clear the message input
            messageInput.value = '';
        });

        function sendMessageToGroup(groupId, message) {
            // You need to implement this function to send a message to the selected group
            // For example, you can use AJAX or fetch API to send the message to the server
            // Update the chat box content based on the sent message
            // For simplicity, let's assume the chat box content is updated directly here
            chatBox.innerHTML += '<div><strong>You:</strong> ' + message + ' (Just Now)</div>';
        }
    });
</script>

</body>
</html>
