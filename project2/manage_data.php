<?php
// This helper does not render or expose records when requested directly.
function load_eois() {
    require __DIR__ . '/settings.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $user, $pwd, $sql_db);
    try {
        $connection->set_charset('utf8mb4');
        $result = $connection->query(
            'SELECT EOInumber, job_reference, first_name, last_name, email, status, submitted_at '
            . 'FROM eoi ORDER BY EOInumber DESC'
        );
        try {
            return $result->fetch_all(MYSQLI_ASSOC);
        } finally {
            $result->free();
        }
    } finally {
        $connection->close();
    }
}
