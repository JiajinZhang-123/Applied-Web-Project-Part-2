<?php
require_once __DIR__ . '/auth.php';

// Authenticate before reading any applicant information or rendering HTML.
require_login();
require_once __DIR__ . '/manage_data.php';

function management_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$filterInput = read_management_filters($_GET);
$filters = $filterInput['filters'];
$filterErrors = $filterInput['errors'];
if ($filterErrors) {
    http_response_code(400);
}
$hasFilters = count(array_filter($filters, function ($value) { return $value !== ''; })) > 0;

$applications = [];
$databaseError = false;
try {
    if (!$filterErrors) {
        $applications = load_eois($filters);
    }
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
            <p id="filter-help">Enter a 6-character job reference, a full first name, a full last name, or a combination.
                Filled fields are combined. Leave all fields blank to list every application.</p>
            <div class="management-filter-fields">
                <div>
                    <label for="job-reference">Job reference</label>
                    <input type="text" id="job-reference" name="job_reference" maxlength="6"
                        value="<?php echo management_escape($filters['job_reference']); ?>"
                        aria-describedby="filter-help<?php echo isset($filterErrors['job_reference']) ? ' job-reference-error' : ''; ?>"
                        <?php if (isset($filterErrors['job_reference'])): ?>aria-invalid="true"<?php endif; ?>>
                    <?php if (isset($filterErrors['job_reference'])): ?>
                        <p id="job-reference-error" class="management-field-error"><?php echo management_escape($filterErrors['job_reference']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="first-name">First name</label>
                    <input type="text" id="first-name" name="first_name" maxlength="20"
                        value="<?php echo management_escape($filters['first_name']); ?>"
                        aria-describedby="filter-help<?php echo isset($filterErrors['first_name']) ? ' first-name-error' : ''; ?>"
                        <?php if (isset($filterErrors['first_name'])): ?>aria-invalid="true"<?php endif; ?>>
                    <?php if (isset($filterErrors['first_name'])): ?>
                        <p id="first-name-error" class="management-field-error"><?php echo management_escape($filterErrors['first_name']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="last-name">Last name</label>
                    <input type="text" id="last-name" name="last_name" maxlength="20"
                        value="<?php echo management_escape($filters['last_name']); ?>"
                        aria-describedby="filter-help<?php echo isset($filterErrors['last_name']) ? ' last-name-error' : ''; ?>"
                        <?php if (isset($filterErrors['last_name'])): ?>aria-invalid="true"<?php endif; ?>>
                    <?php if (isset($filterErrors['last_name'])): ?>
                        <p id="last-name-error" class="management-field-error"><?php echo management_escape($filterErrors['last_name']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="management-filter-actions">
                <button type="submit">Filter applications</button>
                <a href="manage.php">Show all applications</a>
            </div>
        </fieldset>
    </form>

    <h2><?php echo $hasFilters ? 'Filtered Expressions of Interest' : 'All Expressions of Interest'; ?></h2>
    <?php if ($filterErrors): ?>
        <p class="error-message" role="alert">Please correct the highlighted filters. No search was performed.</p>
    <?php elseif ($databaseError): ?>
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
