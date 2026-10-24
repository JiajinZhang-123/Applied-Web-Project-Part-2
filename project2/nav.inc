 <!-- Public Navigation Bar -->
 <nav aria-label="Main navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="jobs.php">Jobs</a></li>
                    <li><a href="apply.php">Apply</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>

            <?php if (!empty($showHrLogout)): ?>
                <a href="logout.php" class="hr-login">HR Logout</a>
            <?php else: ?>
                <a href="login.php" class="hr-login">HR Login</a>
            <?php endif; ?>

        </div>
    </header>

    <main aria-label="<?php echo isset($mainLabel) ? htmlspecialchars($mainLabel) : 'Main content'; ?>">
