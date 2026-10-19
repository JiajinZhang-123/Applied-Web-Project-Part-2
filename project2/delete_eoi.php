<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/manage_data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage.php');
    exit;
}

$deleteInput = read_management_filters(['job_reference' => $_POST['delete_job'] ?? '']);
$jobReference = $deleteInput['filters']['job_reference'];
$token = $_POST['token'] ?? '';

if (!is_string($token) || !isset($_SESSION['management_token'])
    || !hash_equals($_SESSION['management_token'], $token)) {
    $_SESSION['management_message'] = 'The form has expired. Please try again.';
} elseif ($deleteInput['errors'] || $jobReference === '' || ($_POST['confirm_delete'] ?? '') !== '1') {
    $_SESSION['management_message'] = 'Enter a valid job reference and confirm the deletion.';
} else {
    try {
        $deleted = delete_eois_by_job($jobReference);
        if ($deleted > 0) {
            $_SESSION['management_message'] = $deleted . ' application(s) deleted for ' . $jobReference . '.';
        } else {
            $_SESSION['management_message'] = 'No matching applications to delete for ' . $jobReference . '.';
        }
    } catch (mysqli_sql_exception $error) {
        $_SESSION['management_message'] = 'Applications could not be deleted. Please try again later.';
    }
}

header('Location: manage.php', true, 303);
exit;
