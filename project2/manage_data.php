<?php
// Reuse the same local connection settings for all HR database operations.
function management_database() {
    require __DIR__ . '/settings.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = mysqli_connect($host, $user, $pwd, $sql_db);
    mysqli_set_charset($connection, 'utf8mb4');
    return $connection;
}

// Read optional search values; a value with no matching record returns no results.
function read_management_filters($input) {
    $filters = [];
    foreach (['job_reference', 'first_name', 'last_name'] as $field) {
        $value = $input[$field] ?? '';
        $filters[$field] = '';
        if (is_string($value)) {
            $filters[$field] = trim($value);
        }
    }
    $filters['job_reference'] = strtoupper($filters['job_reference']);
    return $filters;
}

$managementStatuses = ['New', 'Current', 'Final'];

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
    $connection = management_database();

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

// Update one application using a parameterized SQL statement.
function change_eoi_status($eoiNumber, $status) {
    $connection = management_database();
    $statement = mysqli_prepare($connection, 'UPDATE eoi SET status = ? WHERE EOInumber = ?');
    mysqli_stmt_bind_param($statement, 'si', $status, $eoiNumber);
    mysqli_stmt_execute($statement);
    $changed = mysqli_stmt_affected_rows($statement);
    mysqli_stmt_close($statement);
    mysqli_close($connection);
    return $changed;
}

// Related skill selections are removed by the database's ON DELETE CASCADE.
function delete_eois_by_job($jobReference) {
    $connection = management_database();
    $statement = mysqli_prepare($connection, 'DELETE FROM eoi WHERE job_reference = ?');
    mysqli_stmt_bind_param($statement, 's', $jobReference);
    mysqli_stmt_execute($statement);
    $deleted = mysqli_stmt_affected_rows($statement);
    mysqli_stmt_close($statement);
    mysqli_close($connection);
    return $deleted;
}
