<?php
// fetch_group_details.php

include 'config.php';

// Assuming you have sanitized the input for security
$groupId = $_GET['groupId'];

$stmt_group = $pdo->prepare("SELECT id, group_name, group_description FROM groups WHERE id = ?");
$stmt_group->execute([$groupId]);
$groupDetails = $stmt_group->fetch(PDO::FETCH_ASSOC);

// Return the group details as JSON
echo json_encode($groupDetails);
?>
