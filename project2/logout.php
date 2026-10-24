<?php
require_once __DIR__ . '/auth.php';

// Clear the session and return to the login page

$_SESSION = array();

session_destroy();

header('Location: login.php');
exit;
?>