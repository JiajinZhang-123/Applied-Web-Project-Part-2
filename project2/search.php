<?php
$pageTitle = 'Finova Technologies | Job Search';
$mainLabel = 'Search Jobs';

require_once __DIR__ . '/settings.php';

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed.");
}

include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

<h1>Search Jobs</h1>
<p>Search for available jobs at Finova Technologies.</p>

<form action="search.php" method="get">
    <label for="keyword">Search by job title:</label>
    <input type="text"
           id="keyword"
           name="keyword"
           placeholder="e.g Data Analyst">

    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
    

    $sql = "SELECT * FROM jobs
        WHERE title LIKE ? OR description LIKE ? 
        ORDER BY display_order";

$stmt = mysqli_prepare($conn, $sql);

$searchTerm = "%" . $keyword . "%";

mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    while ($job = mysqli_fetch_assoc($result)) {
    echo "<section class='job'>";

    echo "<h2>" . htmlspecialchars($job['title']) . "</h2>";

    echo "<p>Job Reference: "
        . htmlspecialchars($job['job_reference']) . "</p>";

    echo "<p>" . htmlspecialchars($job['description']) . "</p>";

    echo "<p>Salary: $"
        . number_format($job['salary_min'])
        . " - $"
        . number_format($job['salary_max'])
        . " AUD per year</p>";

    echo "</section>";
    }


} else {
    echo "<p>No matching jobs found.</p>";
}
   mysqli_stmt_close($stmt);
}
 mysqli_close($conn);

include __DIR__ . '/footer.inc';
?>

