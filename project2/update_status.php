<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/manage_data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage.php');
    exit;
}

$eoiNumber = filter_var($_POST['EOInumber'] ?? null, FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
$token = $_POST['token'] ?? '';

if (!is_string($token) || !isset($_SESSION['management_token'])
    || !hash_equals($_SESSION['management_token'], $token)) {
    $_SESSION['management_message'] = 'The form has expired. Please try again.';
} elseif (!$eoiNumber || $eoiNumber < 1 || !is_string($status)
    || !in_array($status, $managementStatuses, true)) {
    $_SESSION['management_message'] = 'Choose a valid application and status.';
} else {
    try {
        $changed = change_eoi_status($eoiNumber, $status);
        if ($changed > 0) {
            $_SESSION['management_message'] = 'Status updated.';
        } else {
            $_SESSION['management_message'] = 'No application was changed. Check its number or current status.';
        }
    } catch (mysqli_sql_exception $error) {
        $_SESSION['management_message'] = 'Status could not be updated. Please try again later.';
    }
}

// Redirect after POST so refreshing the results does not repeat the update.
header('Location: manage.php', true, 303);
exit;
