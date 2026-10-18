<?php
require_once __DIR__ . '/settings.php';

$required = ["jobReferenceNumber", "firstName", "lastName", "email", "phone", "Gender", "dateOfBirth", "streetAddress", "suburbTown", "State", "Postcode"];
$registeredSkills = ["Programming", "Microsoft Excel", "Leadership", "Communication", "Organisation", "Problem Solving", "Teamwork", "Time Management"];
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
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
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
$selectedSkills = isset($_POST["skills"]) && is_array($_POST["skills"]) ? $_POST["skills"] : []; // If the skills array is not set, return an empty array.

$cleanedSkills = [];
foreach ($selectedSkills as $skill) {
    $skill = sanityCheck($skill);
    if (in_array($skill, $registeredSkills, true)) {
        $cleanedSkills[] = $skill;
    }
}
$Skills = implode(", ", $cleanedSkills); // turns the array of skills into a comma-separated string.

if (strlen($otherSkills) > 1000) {
    $errors[] = "Other skills must be under 1000 characters.";
}

// The following code is written by AI.
$errors = [];

if (!preg_match("/^[A-Za-z0-9]{6}$/", $jobReferenceNumber)) {
    $errors[] = "Job reference must be exactly 6 letters or numbers.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $firstName)) {
    $errors[] = "First name must be 1-20 letters only.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $lastName)) {
    $errors[] = "Last name must be 1-20 letters only.";
}

$dobSql = "";
if (!preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $dateOfBirth, $m)) {
    $errors[] = "Date of birth must be in dd/mm/yyyy format.";
} elseif (!checkdate((int)$m[2], (int)$m[1], (int)$m[3])) {
    $errors[] = "Date of birth is not a valid date.";
} else {
    $dobDate = DateTime::createFromFormat("d/m/Y", $dateOfBirth);
    $dobSql = $dobDate->format("Y-m-d"); // MySQL DATE format
}

// Gender: must be one of the three form values
if (!in_array($Gender, ["Male", "Female", "Other"], true)) {
    $errors[] = "Please select a valid gender.";
}

if (!preg_match("/^[A-Za-z0-9\s]{1,40}$/", $streetAddress)) {
    $errors[] = "Street address must be 1-40 letters, numbers or spaces.";
}
if (!preg_match("/^[A-Za-z0-9\s]{1,40}$/", $suburbTown)) {
    $errors[] = "Suburb/town must be 1-40 letters, numbers or spaces.";
}

// State must be valid, and the postcode must be 4 digits that fit that state
$validStates = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
if (!in_array($State, $validStates, true)) {
    $errors[] = "Please select a valid state.";
}

if (!preg_match("/^\d{4}$/", $Postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}

// Email: must pass PHP's check AND have a domain ending (blocks "rahul@raghavan")
$emailRaw = trim($_POST["email"]);
if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ||
    !preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*\.[A-Za-z]{2,}$/", $emailRaw)) {
    $errors[] = "Please enter a valid email address with a domain, e.g. name@example.com.";
}

// Phone: 8-12 digits
if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8-12 digits.";
}

// Skills: at least one valid skill (the server-side version of "required")
if (count($cleanedSkills) === 0) {
    $errors[] = "Please select at least one skill.";
}


$dbConnection = mysqli_connect($host, $user, $pwd, $sql_db)



?>