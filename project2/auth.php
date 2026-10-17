<?php
// Session Authentication Check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function require_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}
/*Check if the user is logged in*/
function is_logged_in() {
    return isset($_SESSION['user']);
}
?>