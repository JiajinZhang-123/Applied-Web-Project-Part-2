<?php
// Read the three optional search fields and report basic input errors.
function read_management_filters($input) {
    $fields = ['job_reference' => 'Job reference', 'first_name' => 'First name', 'last_name' => 'Last name'];
    $filters = [];
    $errors = [];
    foreach ($fields as $field => $label) {
        $filters[$field] = '';
        if (!isset($input[$field])) {
            continue;
        }
        if (!is_string($input[$field])) {
            $errors[] = $label . ' must be a single text value.';
            continue;
        }
        $value = trim($input[$field]);
        if ($field === 'job_reference') {
            $value = strtoupper($value);
            if ($value !== '' && !preg_match('/^[A-Z0-9]{6}$/', $value)) {
                $errors[] = 'Job reference must contain 6 letters or digits.';
            }
        } elseif (mb_strlen($value, 'UTF-8') > 20) {
            $errors[] = $label . ' must contain no more than 20 characters.';
        }
        $filters[$field] = $value;
    }
    return ['filters' => $filters, 'errors' => $errors];
}

// Fixed database column names and their labels for the sorting dropdown.
$managementSortFields = [
    'EOInumber' => 'EOI Number',
    'job_reference' => 'Job Reference',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'email' => 'Email',
    'status' => 'Status',
    'submitted_at' => 'Submitted'
];

// Read applications using the same three optional filters on every query.
function load_eois($filters = [], $sortField = 'EOInumber') {
    global $managementSortFields;
    // Column names cannot be bound as parameters, so use only fixed choices.
    if (!array_key_exists($sortField, $managementSortFields)) {
        $sortField = 'EOInumber';
    }
    if ($sortField === 'EOInumber') {
        $orderBy = 'EOInumber DESC';
    } else {
        $orderBy = $sortField . ' ASC, EOInumber DESC';
    }
    require __DIR__ . '/settings.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = mysqli_connect($host, $user, $pwd, $sql_db);
    mysqli_set_charset($connection, 'utf8mb4');

    $jobReference = $filters['job_reference'] ?? '';
    $firstName = $filters['first_name'] ?? '';
    $lastName = $filters['last_name'] ?? '';

    // A blank filter accepts every value in that column.
    $query = "SELECT * FROM eoi
              WHERE (job_reference = ? OR ? = '')
                AND (first_name = ? OR ? = '')
                AND (last_name = ? OR ? = '')
              ORDER BY " . $orderBy;
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, 'ssssss',
        $jobReference, $jobReference, $firstName, $firstName, $lastName, $lastName);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);
    mysqli_stmt_close($statement);
    // Read selected skills once and group them by their application number.
    $skills = [];
    $skillResult = mysqli_query($connection, 'SELECT EOInumber, skill FROM eoi_skills ORDER BY skill');
    while ($skill = mysqli_fetch_assoc($skillResult)) {
        $skills[$skill['EOInumber']][] = $skill['skill'];
    }
    mysqli_free_result($skillResult);

    foreach ($applications as $index => $application) {
        $eoiNumber = $application['EOInumber'];
        if (isset($skills[$eoiNumber])) {
            $applications[$index]['selected_skills'] = implode(', ', $skills[$eoiNumber]);
        } else {
            $applications[$index]['selected_skills'] = '';
        }
    }

    mysqli_close($connection);
    return $applications;
}
