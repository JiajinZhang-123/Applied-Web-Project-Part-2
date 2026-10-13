<?php
require_once 'auth.php';

// A protected demo page used to verify that the login authentication flow is working

require_login();

$pageTitle = 'Finova Technologies | Welcome';
$mainLabel = 'Welcome';
include 'header.inc';
include 'nav.inc';
?>

    <section class="login-section">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>

        <p>You are logged in as an HR user.</p>

        <p>
            <a href="logout.php" class="apply-btn">Log out</a>
        </p>
    </section>

<?php include 'footer.inc'; ?>