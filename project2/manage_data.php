<?php
// This helper does not render or expose records when requested directly.
function load_eois($filters = []) {
    require __DIR__ . '/settings.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $user, $pwd, $sql_db);
    try {
        $connection->set_charset('utf8mb4');
        $query = 'SELECT EOInumber, job_reference, first_name, last_name, email, status, submitted_at FROM eoi';
        $jobReference = $filters['job_reference'] ?? '';
        if ($jobReference !== '') {
            $query .= ' WHERE job_reference = ?';
        }
        $query .= ' ORDER BY EOInumber DESC';
        $statement = $connection->prepare($query);
        try {
            if ($jobReference !== '') {
                $statement->bind_param('s', $jobReference);
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
