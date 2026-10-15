<?php
require_once __DIR__ . '/settings.php';

$required = ["jobReferenceNumber", "firstName", "lastName", "email", "phone", "Gender", "dateOfBirth", "streetAddress", "suburbTown", "State", "Postcode"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit;
}

function sanityCheck($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>