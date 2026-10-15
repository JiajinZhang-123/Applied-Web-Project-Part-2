<?php
// Reject malformed filters before attempting any database connection.
function read_management_filters($input) {
    $filters = ['job_reference' => '', 'first_name' => '', 'last_name' => ''];
    $errors = [];
    foreach ($filters as $field => $default) {
        if (!array_key_exists($field, $input)) {
            continue;
        }
        if (!is_string($input[$field])) {
            $errors[$field] = 'Enter a single text value.';
            continue;
        }
        $value = trim($input[$field]);
        $filters[$field] = $value;
        if (!preg_match('//u', $value)) {
            $errors[$field] = 'Enter valid UTF-8 text.';
        } elseif ($field === 'job_reference') {
            $filters[$field] = strtoupper($value);
            if ($value !== '' && !preg_match('/^[A-Za-z0-9]{6}$/D', $value)) {
                $errors[$field] = 'Use exactly 6 letters or digits, for example FT0001.';
            }
        } elseif (!preg_match('/^.{0,20}$/usD', $value)) {
            $errors[$field] = 'Use no more than 20 characters.';
        } elseif (preg_match('/[\x00-\x1F\x7F]/', $value)) {
            $errors[$field] = 'Names must not contain control characters.';
        }
    }
    return ['filters' => $filters, 'errors' => $errors];
}

// Read applications using the same three optional filters on every query.
function load_eois($filters = []) {
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
              ORDER BY EOInumber DESC";
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
