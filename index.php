<?php
include_once("Database.php");

$db = new Database();

$admins = $db->query("Select * from admins where admin_id > ?", "i", 0);
echo json_encode($admins);
