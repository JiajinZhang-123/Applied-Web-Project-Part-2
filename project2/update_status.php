<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/manage_data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage.php');
    exit;
}

// Rebuild the return URL from known form fields, never from an arbitrary URL.
$returnInput = read_management_filters($_POST);
$returnParameters = [];
if (!$returnInput['errors']) {
    $returnParameters = $returnInput['filters'];
}
if (isset($_POST['sort']) && is_string($_POST['sort'])
    && array_key_exists($_POST['sort'], $managementSortFields)) {
    $returnParameters['sort'] = $_POST['sort'];
}
$returnUrl = 'manage.php';
if ($returnParameters) {
    $returnUrl .= '?' . http_build_query($returnParameters);
}

$eoiNumber = filter_var($_POST['EOInumber'] ?? null, FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
$token = $_POST['token'] ?? '';

if (!is_string($token) || !isset($_SESSION['management_token'])
    || !hash_equals($_SESSION['management_token'], $token)) {
    $_SESSION['status_message'] = 'The form has expired. Please try again.';
} elseif (!$eoiNumber || $eoiNumber < 1 || !is_string($status)
    || !in_array($status, $managementStatuses, true)) {
    $_SESSION['status_message'] = 'Choose a valid application and status.';
} else {
    try {
        $result = change_eoi_status($eoiNumber, $status);
        if ($result === 'updated') {
            $_SESSION['status_message'] = 'Status updated.';
        } elseif ($result === 'unchanged') {
            $_SESSION['status_message'] = 'The application already has the selected status.';
        } else {
            $_SESSION['status_message'] = 'Application not found. It may have been deleted.';
        }
    } catch (mysqli_sql_exception $error) {
        error_log('HR status update: ' . $error->getMessage());
        $_SESSION['status_message'] = 'Status could not be updated. Please try again later.';
    }
}

// Redirect after POST so refreshing the results does not repeat the update.
header('Location: ' . $returnUrl, true, 303);
exit;
