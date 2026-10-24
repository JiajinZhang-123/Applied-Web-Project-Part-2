<?php
$pageTitle = 'Finova Technologies | Jobs';
$mainLabel = 'Job listings';

require_once __DIR__ . '/settings.php';


$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed.");
}


$sql = "SELECT * FROM jobs ORDER BY display_order";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Unable to retrieve jobs.");
}

include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>


<section class="jobs jobs-intro">
    <h1 style="letter-spacing: 0.02em;">
        Careers at Finova Technologies
    </h1>

    <p>
        Explore our current FinTech career opportunities and
        find a role that matches your skills and interests.
    </p>

    <h2>Available Jobs</h2>
    <a href="search.php" class="search-jobs-link">
        Search Jobs
    </a>
    
</section>

<div class="jobs-layout">

    <div class="job-list">

        <?php while ($job = mysqli_fetch_assoc($result)): ?>

            <section class="job">

                <h2>
                    <?php echo htmlspecialchars($job['title']); ?>
                </h2>

                <p>
                    <strong>Reference Number:</strong>
                    <?php echo htmlspecialchars($job['job_reference']); ?>
                </p>

                <p>
                    <?php echo htmlspecialchars($job['description']); ?>
                </p>

                <p>
                    <strong>Salary:</strong>
                    $<?php echo number_format($job['salary_min']); ?>
                    -
                    $<?php echo number_format($job['salary_max']); ?>
                    per <?php echo htmlspecialchars($job['salary_period']); ?>
                </p>

                <p>
                    <strong>Reports To:</strong>
                    <?php echo htmlspecialchars($job['reports_to']); ?>
                </p>

                <?php
                
                $itemTypes = [
                    'responsibility' => 'Key Responsibilities',
                    'essential' => 'Essential Requirements',
                    'preferable' => 'Preferable Requirements'
                ];

                
                $itemSql = "SELECT item_text
                            FROM job_items
                            WHERE job_reference = ?
                            AND item_type = ?
                            ORDER BY display_order";

                $stmt = mysqli_prepare($conn, $itemSql);

                foreach ($itemTypes as $type => $heading) {

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ss",
                        $job['job_reference'],
                        $type
                    );

                    mysqli_stmt_execute($stmt);

                    $items = mysqli_stmt_get_result($stmt);

                    echo "<h3>" . htmlspecialchars($heading) . "</h3>";

                    
                    if ($type === 'responsibility') {
                        echo "<ol>";
                    } else {
                        echo "<ul>";
                    }

                    
                    while ($item = mysqli_fetch_assoc($items)) {
                        echo "<li>" .
                            htmlspecialchars($item['item_text']) .
                            "</li>";
                    }

                    if ($type === 'responsibility') {
                        echo "</ol>";
                    } else {
                        echo "</ul>";
                    }
                }

                mysqli_stmt_close($stmt);
                ?>

            </section>

        <?php endwhile; ?>

    </div>

    <aside class="career-tips">
        <h2>Career Tips</h2>

        <p>
            Before applying, learn about Finova Technologies
            and make sure your application matches the role.
        </p>

        <ul>
            <li>Keep your resume clear and up to date.</li>
            <li>Highlight skills that match the position.</li>
            <li>
                Prepare examples of teamwork and
                problem-solving.
            </li>
        </ul>
    </aside>

</div>

<?php
mysqli_free_result($result);
mysqli_close($conn);

include __DIR__ . '/footer.inc';
?>