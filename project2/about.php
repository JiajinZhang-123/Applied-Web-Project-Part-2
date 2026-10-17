<?php

require_once 'settings.php';

// connect databse
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

$pageTitle = 'Finova Technologies | About the Team';
$mainLabel = 'About the team';
include 'header.inc';
include 'nav.inc';

if (!$dbconn) {
    echo '<p class="error-message">Unable to connect to the database.</p>';
    die();
}

mysqli_set_charset($dbconn, 'utf8mb4');
?>

    <!-- Page title -->
    <section class="about-hero">
        <h1 style="letter-spacing: 0.02em;">About the Team</h1>

        <p class="about-intro">
            Meet the students behind Finova Technologies.
            We are a group of four Swinburne students working
            together on the COS10026 Web Technology Project.
        </p>
    </section>

    <!-- Group information（from team_info tabel） -->
    <section class="about-section">

        <h2>Group Information</h2>

        <?php
        if ($dbconn) {
            $query = "SELECT group_name, tutorial_time, tutor_name FROM team_info WHERE team_id = 1";

            // Search
            $result = mysqli_query($dbconn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                ?>
                <ul class="group-info">
                    <li>
                        <strong>Group Name:</strong>
                        <?php echo htmlspecialchars($row['group_name']); ?>
                        <ul>
                            <li>
                                <strong>Tutorial Class Day/Time:</strong>
                                <?php echo htmlspecialchars($row['tutorial_time']); ?>
                            </li>
                            <li>
                                <strong>Tutor:</strong>
                                <?php echo htmlspecialchars($row['tutor_name']); ?>
                            </li>
                        </ul>
                    </li>
                </ul>
                <?php
            } else {
                echo '<p>Group information is not available.</p>';
            }

            mysqli_free_result($result);
        } else {
            echo '<p>Group information is not available.</p>';
        }
        ?>

    </section>

    <!-- Group photo -->
    <section class="about-section">

        <h2>Our Team Photo</h2>

        <figure class="group-figure">
            <img src="images/group.jpg" alt="Group photo of the Finova Technologies student team." width="800">
            <figcaption>
                The brain cells team &mdash; COS10026 Group G04,
                Semester 2, 2026.
            </figcaption>
        </figure>

    </section>

    <!-- Member contributions（from team_members + member_contributions table） -->
    <section class="about-section">

        <h2>Member Contributions</h2>

        <table class="contributions">
            <caption>
                Contributions of each member to both project parts
            </caption>

            <thead>
                <tr>
                    <th scope="col">Member</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Contribution Part 1</th>
                    <th scope="col">Contribution Part 2</th>
                </tr>
            </thead>

            <tbody>
                <?php

                // Aggregate the two contribution rows (Part 1 / Part 2) into one row per member
                $query = "SELECT m.full_name, m.student_id,
                                 MAX(CASE WHEN c.project_part = 'Part 1' THEN c.contribution END) AS part1,
                                 MAX(CASE WHEN c.project_part = 'Part 2' THEN c.contribution END) AS part2
                          FROM team_members m
                          LEFT JOIN member_contributions c ON m.member_id = c.member_id
                          GROUP BY m.member_id, m.full_name, m.student_id
                          ORDER BY m.display_order";

                $result = mysqli_query($dbconn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    function render_contribution($value) {
                        if (empty($value)) {
                            return '<span class="pending">Pending...</span>';
                        }
                        return htmlspecialchars($value);
                    }
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<th scope="row">' . htmlspecialchars($row['full_name']) . '</th>';
                        echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                        echo '<td>' . render_contribution($row['part1']) . '</td>';
                        echo '<td>' . render_contribution($row['part2']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">Member contributions are not available.</td></tr>';
                }

                mysqli_free_result($result);
                ?>
            </tbody>
        </table>

    </section>

    <!-- Quotes from each member -->
    <section class="about-section">

        <h2>Quotes From Our Members</h2>

        <p>
            Each member shares a quote in their first language
            (or a language of their choice), with an English
            translation.
        </p>

        <?php
        $query = "SELECT m.full_name, q.original_quote, q.english_translation
                  FROM member_quotes q
                  JOIN team_members m ON q.member_id = m.member_id
                  ORDER BY m.display_order";

        $result = mysqli_query($dbconn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <article class="quote-card">
                    <p class="quote-original">&ldquo;<?php echo htmlspecialchars($row['original_quote']); ?>&rdquo;</p>
                    <p class="quote-author"><?php echo htmlspecialchars($row['full_name']); ?></p>
                    <p class="quote-english">
                        English: <?php echo htmlspecialchars($row['english_translation']); ?>
                    </p>
                </article>
                <?php
            }
        } else {
            echo '<p>Quotes are not available.</p>';
        }

        mysqli_free_result($result);
        ?>

    </section>

    <!-- Fun facts table -->
    <section class="about-section">

        <h2>Fun Facts About Us</h2>

        <table class="fun-facts">
            <caption>
                Fun facts about the members of The brain cells
            </caption>

            <thead>
                <tr>
                    <th scope="col">Member</th>
                    <th scope="col">Fun Fact 1</th>
                    <th scope="col">Fun Fact 2</th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Aggregate the two fun-fact rows into one row per member
                $query = "SELECT m.full_name,
                                 MAX(CASE WHEN f.display_order = 1 THEN f.fact_text END) AS fact1,
                                 MAX(CASE WHEN f.display_order = 2 THEN f.fact_text END) AS fact2
                          FROM team_members m
                          LEFT JOIN member_fun_facts f ON m.member_id = f.member_id
                          GROUP BY m.member_id, m.full_name
                          ORDER BY m.display_order";

                $result = mysqli_query($dbconn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<th scope="row">' . htmlspecialchars($row['full_name']) . '</th>';
                        echo '<td>' . htmlspecialchars($row['fact1']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['fact2']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Fun facts are not available.</td></tr>';
                }

                mysqli_free_result($result);
                ?>
            </tbody>
        </table>

    </section>

<?php
mysqli_close($dbconn);
include 'footer.inc';
?>