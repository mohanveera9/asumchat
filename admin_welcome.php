<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title >Welcome Admin</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body background: linear-gradient(to left, #728FCE, #C34A2C, #342D7E)>
<div class="container" >
    <h1 align="center">Welcome, Admin!</h1>
    <p>As an admin, you have full control over the group.</p>
    <ul>
        <li align="center"><a href="add_friend.php">Add Members to Group</a></li>
        <li align="center"><a href="remove_friend.php">Remove Members from Group</a></li>
    </ul>
    <form action="group_chat.php" method="post">
        <button type="submit">Start Chat Now</button>
    </form>
</div>
</body>
</html>