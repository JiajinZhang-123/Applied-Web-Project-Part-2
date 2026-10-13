<?php
// Session Authentication Check

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*Check if the user is logged in*/
function require_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function is_logged_in() {
    return isset($_SESSION['user']);
}
?>