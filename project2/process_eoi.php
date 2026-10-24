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
    $data = htmlspecialchars($data); 
    return $data;
}

// Check that a postcode falls inside the ranges used by the selected state.
function postcode_matches_state($postcode, $state) {
    $ranges = [
        'VIC' => ['3000-3999', '8000-8999'],
        'NSW' => ['2000-2599', '1000-1999'],
        'QLD' => ['4000-4999', '9000-9999'],
        'ACT' => ['0200-0299', '2600-2619'],
        'SA'  => ['5000-5999'],
        'WA'  => ['6000-6799'],
        'TAS' => ['7000-7999'],
        'NT'  => ['0800-0899'],
    ];
    if (!isset($ranges[$state])) {
        return false;
    }
    $postcodeNum = (int) $postcode;
    foreach ($ranges[$state] as $range) {
        [$low, $high] = explode('-', $range);
        if ($postcodeNum >= (int) $low && $postcodeNum <= (int) $high) {
            return true;
        }
    }
    return false;
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

$otherSkills = isset($_POST["otherSkills"]) ? sanityCheck($_POST["otherSkills"]) : ""; 
$selectedSkills = isset($_POST["skills"]) && is_array($_POST["skills"]) ? $_POST["skills"] : []; //

$cleanedSkills = [];
foreach ($selectedSkills as $skill) {
    $skill = sanityCheck($skill);
    if (in_array($skill, $registeredSkills, true)) {
        $cleanedSkills[] = $skill;
    }
}

// The following code is assisted by AI.
$errors = [];

if (strlen($otherSkills) > 1000) {
    $errors[] = "Other skills must be under 1000 characters.";
}

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

    // Applicants must be at least 15 years old.
    $minDob = (new DateTime('now'))->sub(new DateInterval('P15Y'));
    if ($dobDate > $minDob) {
        $errors[] = "Applicants must be at least 15 years old.";
    }
}

if (!in_array($Gender, ["Male", "Female", "Other"], true)) {
    $errors[] = "Please select a valid gender.";
}

if (!preg_match("/^[A-Za-z0-9\s]{1,40}$/", $streetAddress)) {
    $errors[] = "Street address must be 1-40 letters, numbers or spaces.";
}
if (!preg_match("/^[A-Za-z0-9\s]{1,40}$/", $suburbTown)) {
    $errors[] = "Suburb/town must be 1-40 letters, numbers or spaces.";
}

$validStates = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
if (!in_array($State, $validStates, true)) {
    $errors[] = "Please select a valid state.";
}

if (!preg_match("/^\d{4}$/", $Postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (!postcode_matches_state($Postcode, $State)) {
    $errors[] = "The postcode does not match the selected state.";
}

$emailRaw = trim($_POST["email"]);
if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ||
    !preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*\.[A-Za-z]{2,}$/", $emailRaw)) {
    $errors[] = "Please enter a valid email address with a domain, e.g. name@example.com.";
}

if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8-12 digits.";
}

if (count($cleanedSkills) === 0) {
    $errors[] = "Please select at least one skill.";
}


$dbConnection = mysqli_connect($host, $user, $pwd, $sql_db);

if (empty($errors) && $dbConnection) { // Done with AI assistance
    $stmt = mysqli_prepare($dbConnection, "SELECT 1 FROM jobs WHERE job_reference = ?"); 

    mysqli_stmt_bind_param($stmt, "s", $jobReferenceNumber); 

    mysqli_stmt_execute($stmt); 

    mysqli_stmt_store_result($stmt); // 

    if (mysqli_stmt_num_rows($stmt) === 0) {
        $errors[] = "That job reference number does not exist.";
    }
    mysqli_stmt_close($stmt);
}

if (!empty($errors)) { // Done with AI assistance
    if ($dbConnection) {
        mysqli_close($dbConnection);
    }

    $pageTitle = 'Finova Technologies | Application Error';
    $mainLabel = 'Application error';
    include __DIR__ . '/header.inc';
    include __DIR__ . '/nav.inc';
    ?>
    <section class="eoi-result">
        <h1>Application could not be submitted</h1>
        <ul>
            <?php foreach ($errors as $error) { echo "<li>" . $error . "</li>"; } ?>
        </ul>
        <p><a href="apply.php">Go back to the form</a></p>
    </section>
    <?php
    include __DIR__ . '/footer.inc';
    exit;
}

// Done with AI assistance
mysqli_query($dbConnection, "CREATE TABLE IF NOT EXISTS eoi ( 
  EOInumber INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_reference CHAR(6) NOT NULL,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  date_of_birth DATE NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  street_address VARCHAR(40) NOT NULL,
  suburb_town VARCHAR(40) NOT NULL,
  state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode CHAR(4) NOT NULL,
  email VARCHAR(254) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  other_skills TEXT NULL,
  status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (EOInumber),
  KEY idx_eoi_job (job_reference),
  KEY idx_eoi_first_last (first_name, last_name),
  KEY idx_eoi_last (last_name),
  KEY idx_eoi_status (status),
  CONSTRAINT fk_eoi_job FOREIGN KEY (job_reference)
    REFERENCES jobs (job_reference) ON DELETE RESTRICT
) ENGINE=InnoDB");
// Done with AI assistance
mysqli_query($dbConnection, "CREATE TABLE IF NOT EXISTS eoi_skills (
  EOInumber INT UNSIGNED NOT NULL,
  skill ENUM('Programming','Microsoft Excel','Leadership','Communication',
    'Organisation','Problem Solving','Teamwork','Time Management') NOT NULL,
  PRIMARY KEY (EOInumber, skill),
  CONSTRAINT fk_eoi_skills_eoi FOREIGN KEY (EOInumber)
    REFERENCES eoi (EOInumber) ON DELETE CASCADE
) ENGINE=InnoDB");

// Insert the EOI and its skills in one transaction, so a failure does not
// leave an application record without its selected skills.
mysqli_begin_transaction($dbConnection);

$stmt = mysqli_prepare($dbConnection,
    "INSERT INTO eoi (job_reference, first_name, last_name, date_of_birth, gender,
        street_address, suburb_town, state, postcode, email, phone, other_skills)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssssssssssss",
    $jobReferenceNumber, $firstName, $lastName, $dobSql, $Gender,
    $streetAddress, $suburbTown, $State, $Postcode, $email, $phone, $otherSkills);
$success = mysqli_stmt_execute($stmt);
$eoiNumber = mysqli_insert_id($dbConnection); // the auto-generated EOInumber
mysqli_stmt_close($stmt);

// Done with AI assistance
if ($success) {
    foreach ($cleanedSkills as $skill) {
        $skillStmt = mysqli_prepare($dbConnection, "INSERT INTO eoi_skills (EOInumber, skill) VALUES (?, ?)");
        mysqli_stmt_bind_param($skillStmt, "is", $eoiNumber, $skill);
        if (!mysqli_stmt_execute($skillStmt)) {
            $success = false;
            break;
        }
        mysqli_stmt_close($skillStmt);
    }
}

if ($success) {
    mysqli_commit($dbConnection);
} else {
    mysqli_rollback($dbConnection);
}

mysqli_close($dbConnection);

$pageTitle = 'Finova Technologies | Application Submitted';
$mainLabel = 'Application confirmation';
include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>
<section class="eoi-result">
    <?php if ($success) { ?>
        <h1>Thank you, <?php echo $firstName; ?>!</h1>
        <p>Your application has been received.</p>
        <p><strong>Your EOI number is: <?php echo $eoiNumber; ?></strong></p>
    <?php } else { ?>
        <h1>Something went wrong</h1>
        <p>We couldn't save your application. Please try again.</p>
    <?php } ?>
    <p><a href="index.php">Back to home</a></p>
</section>
<?php include __DIR__ . '/footer.inc';
?>