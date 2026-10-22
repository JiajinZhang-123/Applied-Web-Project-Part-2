<?php
require_once __DIR__ . '/auth.php';
// Display the HR Login Form

if (is_logged_in()) {
    header('Location: manage.php');
    exit;
}

$pageTitle = 'Finova Technologies | HR Login';
$mainLabel = 'HR login';
include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

    <section class="login-section">
        <h1>HR Login</h1>

        <p>Please enter your HR credentials to access the application management page.</p>

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error-message">Incorrect username or password. Please try again.</p>';
        }
        ?>

        <form method="post" action="process_login.php" class="login-form">
            <div class="nameInputs">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="nameInputs">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="apply-btn">Log in</button>
        </form>
    </section>

<?php include __DIR__ . '/footer.inc'; ?>