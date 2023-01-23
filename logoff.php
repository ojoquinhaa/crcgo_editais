<?php
session_start();
header("Location: /editais/admin.php");
$_SESSION['logged'] = 0;
exit;
?>