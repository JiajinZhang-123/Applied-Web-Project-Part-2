<?php
require_once __DIR__ . '/settings.php';

$required = ["jobReferenceNumber", "firstName", "lastName", "email", "phone", "Gender", "dateOfBirth", "streetAddress", "suburbTown", "State", "Postcode"];
$registeredSkills = ["Programming", "Microsoft Excel", "Leadership", "Communication", "Organisation", "Problem Solving", "Teamwork", "Time Management"]
foreach ($required as $field) { 
    if (!isset($_POST[$field])) {
        header("Location: apply.php");
        exit;
    }
}

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

$jobReferenceNumber = sanityCheck($_POST["jobReferenceNumber"]);
$firstName = sanityCheck($_POST["firstName"]);
$lastName = sanityCheck($_POST["lastName"]);
$email = sanityCheck($_POST["email"]);
$phone = sanityCheck($_POST["phone"]);
$Gender = sanityCheck($_POST["Gender"]);
$dateOfBirth = sanityCheck($_POST["dateOfBirth"]);
$streetAddress = sanityCheck($_POST["streetAddress"]);
$suburbTown = sanityCheck($_POST["suburbTown"]);
$State = sanityCheck($_POST["State"]);
$Postcode = sanityCheck($_POST["Postcode"]);

$otherSkills = isset($_POST["otherSkills"]) ? sanityCheck($_POST["otherSkills"]) : ""; // ? is an "if" statement shortened, if else then return an empty string.
$selectedSkills = isset($_POST["skills"]) ? $_POST["skills"] : []; // If the skills array is not set, return an empty array.

$cleanedSkills = [];
foreach ($selectedSkills as $skill) {
    $skill = sanityCheck($skill);
    if (in_array($skill, $registeredSkills, true)) {
        $cleanedSkills[] = $skill;
    }
}
$Skills = implode(", ", $cleanedSkills); // turns the array of skills into a comma-separated string.



?>