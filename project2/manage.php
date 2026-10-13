<?php
require_once __DIR__ . '/auth.php';

// Check access before sending HTML or reading application data.
require_login();

$pageTitle = 'Finova Technologies | HR Management';
$mainLabel = 'HR application management';
include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

<section class="management-section">
    <h1>HR Application Management</h1>
    <p>Review expressions of interest submitted to Finova Technologies.</p>
    <p>Signed in as <?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>.
        <a href="logout.php">Log out</a>
    </p>
</section>

<?php include __DIR__ . '/footer.inc'; ?>
