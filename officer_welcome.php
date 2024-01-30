<!-- officer_welcome.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Officer</title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
<div class="container" >
    <h1>Welcome, Officer!</h1>
    <p>As an officer, you have certain privileges in managing the group.</p>
    <ul>
       
    <li><a href="#">Add Members to Group</a></li>
        <li><a href="#">Remove Members from Group</a></li>
    </ul>
    <form action="group_chat.php" method="post">
        <button type="submit">Start Chat Now</button>
    </form>
</div>
</body>
</html>