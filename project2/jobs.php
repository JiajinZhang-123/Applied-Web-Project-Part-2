
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pageTitle = 'Finova Technologies | Jobs';
$mainLabel = 'Job listings';

require_once __DIR__ . '/settings.php';

$conn = mysqli_connect($host,$user,$pwd,$sql_db);

if (!$conn) { 
    die("Database connection fialed.");

}

$sql = "SELECT * FROM jobs ORDER BY display_order";
$result = mysqli_query($conn,$sql);

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
</section>

<div class="jobs-layout">

    <div class="job-list">
       <?php while ($job = mysqli_fetch_assoc($result)): ?>

    <section class="job">

        <h2><?php echo htmlspecialchars($job['title']); ?></h2>

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

    </section>
<?php endwhile; ?>


<section class="job">
            <h2>Data Analyst</h2>

            <p><strong>Reference Number:</strong> FT0001</p>

            <p>
                The Data Analyst supports Finova Technologies by
                analysing financial and customer data to help the
                company make better business decisions.
            </p>

            <p>
                <strong>Salary:</strong>
                $75,000 - $90,000 per year
            </p>

            <p>
                <strong>Reports To:</strong>
                Senior Data Manager
            </p>

            <h3>Key Responsibilities</h3>
            <ol>
                <li>Analyse financial and customer data.</li>
                <li>Create reports and dashboards.</li>
                <li>Identify trends and patterns in business data.</li>
                <li>Present findings to managers and team members.</li>
            </ol>

            <h3>Essential Requirements</h3>
            <ul>
                <li>Knowledge of SQL and Excel.</li>
                <li>Strong analytical and problem-solving skills.</li>
                <li>Good written and verbal communication skills.</li>
                <li>Ability to work effectively in a team.</li>
            </ul>

            <h3>Preferable Requirements</h3>
            <ul>
                <li>Experience with Power BI or Tableau.</li>
                <li>Knowledge of financial technology services.</li>
                <li>Basic knowledge of Python.</li>
            </ul>
        </section>

        <section class="job">
            <h2>Software Developer</h2>

            <p><strong>Reference Number:</strong> FT0002</p>

            <p>
                The Software Developer designs, develops and
                maintains secure digital applications used by
                Finova Technologies customers and employees.
            </p>

            <p>
                <strong>Salary:</strong>
                $80,000 - $100,000 per year
            </p>

            <p>
                <strong>Reports To:</strong>
                Software Development Manager
            </p>

            <h3>Key Responsibilities</h3>
            <ol>
                <li>Develop and maintain financial applications.</li>
                <li>Test and debug software systems.</li>
                <li>Work with team members to develop new features.</li>
                <li>Maintain secure and reliable application code.</li>
            </ol>

            <h3>Essential Requirements</h3>
            <ul>
                <li>
                    Knowledge of programming languages such as
                    JavaScript, Java or C#.
                </li>
                <li>Good problem-solving skills.</li>
                <li>Understanding of software development principles.</li>
                <li>Ability to work effectively in a development team.</li>
            </ul>

            <h3>Preferable Requirements</h3>
            <ul>
                <li>Experience with web application development.</li>
                <li>Knowledge of Git and GitHub.</li>
                <li>Understanding of financial technology systems.</li>
            </ul>
        </section>

        <section class="job">
            <h2>Cybersecurity Analyst</h2>

            <p><strong>Reference Number:</strong> FT0003</p>

            <p>
                The Cybersecurity Analyst helps protect Finova
                Technologies' systems, customer information and
                financial data from security threats.
            </p>

            <p>
                <strong>Salary:</strong>
                $85,000 - $105,000 per year
            </p>

            <p>
                <strong>Reports To:</strong>
                Cybersecurity Manager
            </p>

            <h3>Key Responsibilities</h3>
            <ol>
                <li>Monitor systems for possible security threats.</li>
                <li>Identify and respond to security incidents.</li>
                <li>Protect customer and financial information.</li>
                <li>Assist with security reviews and risk assessments.</li>
            </ol>

            <h3>Essential Requirements</h3>
            <ul>
                <li>Knowledge of cybersecurity principles.</li>
                <li>Understanding of network security.</li>
                <li>Strong attention to detail.</li>
                <li>Good analytical and problem-solving skills.</li>
            </ul>

            <h3>Preferable Requirements</h3>
            <ul>
                <li>Knowledge of cloud security.</li>
                <li>Experience using security monitoring tools.</li>
                <li>Understanding of financial data security.</li>
            </ul>
        </section>

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
include __DIR__ . '/footer.inc';
?>
