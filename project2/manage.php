<?php
require_once __DIR__ . '/auth.php';

// Authenticate before reading any applicant information or rendering HTML.
require_login();
require_once __DIR__ . '/manage_data.php';

// The hidden token ties a management operation to this logged-in browser session.
if (!isset($_SESSION['management_token'])) {
    $_SESSION['management_token'] = bin2hex(random_bytes(16));
}
$managementMessage = $_SESSION['management_message'] ?? '';
unset($_SESSION['management_message']);

function management_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$filters = read_management_filters($_GET);
$sortField = 'EOInumber';
if (isset($_GET['sort']) && is_string($_GET['sort'])
    && array_key_exists($_GET['sort'], $managementSortFields)) {
    $sortField = $_GET['sort'];
}

$applications = [];
$databaseError = false;
try {
    $applications = load_eois($filters, $sortField);
} catch (mysqli_sql_exception $error) {
    $databaseError = true;
}

$pageTitle = 'Finova Technologies | HR Management';
$mainLabel = 'HR application management';
$showHrLogout = true;
include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

<section class="management-section">
    <h1>HR Application Management</h1>
    <p>Review expressions of interest submitted to Finova Technologies.</p>

    <?php if ($managementMessage !== ''): ?>
        <p role="status"><?php echo management_escape($managementMessage); ?></p>
    <?php endif; ?>

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
                        aria-describedby="filter-help">
                </div>
                <div>
                    <label for="first-name">First name</label>
                    <input type="text" id="first-name" name="first_name" maxlength="20"
                        value="<?php echo management_escape($filters['first_name']); ?>"
                        aria-describedby="filter-help">
                </div>
                <div>
                    <label for="last-name">Last name</label>
                    <input type="text" id="last-name" name="last_name" maxlength="20"
                        value="<?php echo management_escape($filters['last_name']); ?>"
                        aria-describedby="filter-help">
                </div>
                <div>
                    <label for="sort-field">Sort by</label>
                    <select id="sort-field" name="sort" aria-describedby="sort-help">
                        <?php foreach ($managementSortFields as $field => $label): ?>
                            <option value="<?php echo management_escape($field); ?>"<?php if ($sortField === $field): ?> selected<?php endif; ?>>
                                <?php echo management_escape($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <p id="sort-help">EOI numbers are shown newest first. Other fields use ascending order.</p>
            <div class="management-filter-actions">
                <button type="submit">Filter applications</button>
                <a href="manage.php">Show all applications</a>
            </div>
        </fieldset>
    </form>

    <section id="delete-applications" class="management-delete">
        <h2>Delete applications by job reference</h2>
        <p>This deletes all applications for the given job, including their selected skills.</p>
        <form action="delete_eoi.php" method="post" class="management-filters">
            <input type="hidden" name="token" value="<?php echo management_escape($_SESSION['management_token']); ?>">
            <label for="delete-job">Job reference to delete</label>
            <input type="text" id="delete-job" name="delete_job" maxlength="6" required>
            <p>
                <label>
                    <input type="checkbox" name="confirm_delete" value="1" required>
                    I confirm the deletion and understand it cannot be undone.
                </label>
            </p>
            <button type="submit">Delete applications</button>
        </form>
    </section>

    <h2>Applications</h2>
    <?php if ($databaseError): ?>
        <p class="error-message" role="alert">Applications could not be loaded. Please try again later.</p>
    <?php elseif (!$applications): ?>
        <p>No applications found.</p>
    <?php else: ?>
        <p><?php echo count($applications); ?> application(s) found.</p>
        <p class="management-scroll-hint">On small screens, scroll the table sideways to see all columns.</p>
        <div class="management-table-wrapper" role="region" aria-label="Application records" tabindex="0">
            <table class="management-table">
                <caption>
                    Application results sorted by <?php echo management_escape($managementSortFields[$sortField]); ?>
                    <?php if ($sortField === 'EOInumber'): ?>(newest first)<?php else: ?>(ascending)<?php endif; ?>
                </caption>
                <thead>
                    <tr>
                        <th scope="col">EOI Number</th>
                        <th scope="col">Job Reference</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Application Details</th>
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
                            <td>
                                <form action="update_status.php" method="post" class="management-status-form">
                                    <input type="hidden" name="EOInumber" value="<?php echo management_escape($application['EOInumber']); ?>">
                                    <input type="hidden" name="token" value="<?php echo management_escape($_SESSION['management_token']); ?>">
                                    <label for="status-<?php echo management_escape($application['EOInumber']); ?>">Status</label>
                                    <select id="status-<?php echo management_escape($application['EOInumber']); ?>" name="status">
                                        <?php foreach ($managementStatuses as $status): ?>
                                            <option value="<?php echo management_escape($status); ?>"<?php if ($application['status'] === $status): ?> selected<?php endif; ?>>
                                                <?php echo management_escape($status); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                            <td><?php echo management_escape($application['submitted_at']); ?></td>
                            <td>
                                <details>
                                    <summary>View application #<?php echo management_escape($application['EOInumber']); ?></summary>
                                    <dl class="management-details">
                                        <dt>Date of birth</dt>
                                        <dd><?php echo management_escape($application['date_of_birth']); ?></dd>
                                        <dt>Gender</dt>
                                        <dd><?php echo management_escape($application['gender']); ?></dd>
                                        <dt>Phone</dt>
                                        <dd><?php echo management_escape($application['phone']); ?></dd>
                                        <dt>Street address</dt>
                                        <dd><?php echo management_escape($application['street_address']); ?></dd>
                                        <dt>Suburb / town</dt>
                                        <dd><?php echo management_escape($application['suburb_town']); ?></dd>
                                        <dt>State</dt>
                                        <dd><?php echo management_escape($application['state']); ?></dd>
                                        <dt>Postcode</dt>
                                        <dd><?php echo management_escape($application['postcode']); ?></dd>
                                        <dt>Selected skills</dt>
                                        <dd><?php
                                            if ($application['selected_skills'] !== '') {
                                                echo management_escape($application['selected_skills']);
                                            } else {
                                                echo 'None selected.';
                                            }
                                        ?></dd>
                                        <dt>Other skills</dt>
                                        <dd><?php
                                            if (trim((string) $application['other_skills']) !== '') {
                                                echo management_escape($application['other_skills']);
                                            } else {
                                                echo 'Not provided.';
                                            }
                                        ?></dd>
                                    </dl>
                                </details>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/footer.inc'; ?>
