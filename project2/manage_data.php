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

// This helper does not render or expose records when requested directly.
function load_eois($filters = []) {
    require __DIR__ . '/settings.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $user, $pwd, $sql_db);
    try {
        $connection->set_charset('utf8mb4');
        $query = 'SELECT EOInumber, job_reference, first_name, last_name, email, status, submitted_at FROM eoi';
        $conditions = [];
        $parameters = [];
        // Only these fixed column names may be used in the filter query.
        foreach (['job_reference', 'first_name', 'last_name'] as $field) {
            $value = $filters[$field] ?? '';
            if ($value !== '') {
                $conditions[] = $field . ' = ?';
                $parameters[] = $value;
            }
        }
        if ($conditions) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $query .= ' ORDER BY EOInumber DESC';
        $statement = $connection->prepare($query);
        try {
            if ($parameters) {
                $statement->bind_param(str_repeat('s', count($parameters)), ...$parameters);
            }
            $statement->execute();
            $result = $statement->get_result();
            try {
                return $result->fetch_all(MYSQLI_ASSOC);
            } finally {
                $result->free();
            }
        } finally {
            $statement->close();
        }
    } finally {
        $connection->close();
    }
}
