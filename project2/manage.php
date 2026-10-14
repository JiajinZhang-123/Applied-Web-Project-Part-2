<?php
require_once __DIR__ . '/auth.php';

// Authenticate before reading any applicant information or rendering HTML.
require_login();
require_once __DIR__ . '/manage_data.php';

function management_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$filters = [
    'job_reference' => isset($_GET['job_reference']) && is_string($_GET['job_reference'])
        ? strtoupper(trim($_GET['job_reference'])) : ''
];
$hasFilters = $filters['job_reference'] !== '';

$applications = [];
$databaseError = false;
try {
    $applications = load_eois($filters);
} catch (mysqli_sql_exception $error) {
    error_log('HR application list: ' . $error->getMessage());
    $databaseError = true;
}

$pageTitle = 'Finova Technologies | HR Management';
$mainLabel = 'HR application management';
include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

<section class="management-section">
    <h1>HR Application Management</h1>
    <p>Review expressions of interest submitted to Finova Technologies.</p>
    <p>Signed in as <?php echo management_escape($_SESSION['user']); ?>.
        <a href="logout.php">Log out</a>
    </p>

    <form action="manage.php" method="get" class="management-filters">
        <fieldset>
            <legend>Filter applications</legend>
            <div class="management-filter-fields">
                <div>
                    <label for="job-reference">Job reference</label>
                    <input type="text" id="job-reference" name="job_reference" maxlength="6"
                        value="<?php echo management_escape($filters['job_reference']); ?>">
                </div>
            </div>
            <div class="management-filter-actions">
                <button type="submit">Filter applications</button>
            </div>
        </fieldset>
    </form>

    <h2><?php echo $hasFilters ? 'Filtered Expressions of Interest' : 'All Expressions of Interest'; ?></h2>
    <?php if ($databaseError): ?>
        <p class="error-message" role="alert">Applications could not be loaded. Please try again later.</p>
    <?php elseif (!$applications): ?>
        <p><?php echo $hasFilters ? 'No applications match these filters.' : 'No applications have been submitted yet.'; ?></p>
    <?php else: ?>
        <p><?php echo count($applications); ?> application(s) found.</p>
        <div class="management-table-wrapper" role="region" aria-label="Application records" tabindex="0">
            <table class="management-table">
                <caption>Application results, newest EOI number first</caption>
                <thead>
                    <tr>
                        <th scope="col">EOI Number</th>
                        <th scope="col">Job Reference</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <th scope="row"><?php echo management_escape($application['EOInumber']); ?></th>
                            <td><?php echo management_escape($application['job_reference']); ?></td>
                            <td><?php echo management_escape($application['first_name']); ?></td>
                            <td><?php echo management_escape($application['last_name']); ?></td>
                            <td><?php echo management_escape($application['email']); ?></td>
                            <td><?php echo management_escape($application['status']); ?></td>
                            <td><?php echo management_escape($application['submitted_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/footer.inc'; ?>
