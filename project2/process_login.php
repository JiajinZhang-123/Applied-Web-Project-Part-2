<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/settings.php';

//Only POST submissions are accepted; direct access will redirect to the login page
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
if ($username === '' || $password === '') {
    header('Location: login.php?error=1');
    exit;
}

$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die('<p>Unable to connect to the database.</p>');
}

mysqli_set_charset($dbconn, 'utf8mb4'); // Character Set

$query = "SELECT user_id, password_hash FROM users WHERE username = ?";

// Use prepared statements to prevent SQL injection
$stmt = mysqli_prepare($dbconn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $user_id, $password_hash);
    mysqli_stmt_fetch($stmt);

    if ($user_id && password_verify($_POST['password'], $password_hash)) {
        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $user_id;

        header('Location: manage.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($dbconn);
?>