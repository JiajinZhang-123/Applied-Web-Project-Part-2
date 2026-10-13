<?php

require_once 'settings.php';

// connect databse
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$dbconn) {
    echo '<p>Unable to connect to the database.</p>';
}

mysqli_set_charset($dbconn, 'utf8mb4');

$pageTitle = 'Finova Technologies | About the Team';
$mainLabel = 'About the team';
include 'header.inc';
include 'nav.inc';
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

        <dl class="contributions">
            <?php
            $query = "SELECT m.full_name, m.student_id, c.project_part, c.contribution
                      FROM team_members m
                      LEFT JOIN member_contributions c ON m.member_id = c.member_id
                      ORDER BY m.display_order, c.project_part";

            $result = mysqli_query($dbconn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $currentMember = null;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($currentMember !== $row['full_name']) {
                        if ($currentMember !== null) {
                            echo '</dd>';
                        }
                        $currentMember = $row['full_name'];
                        echo '<dt>';
                        echo htmlspecialchars($row['full_name']);
                        echo ' <span class="student-id">' . htmlspecialchars($row['student_id']) . '</span>';
                        echo '</dt>';
                        echo '<dd>';
                    }
                    if (!empty($row['contribution'])) {
                        echo htmlspecialchars($row['project_part']) . ': ' . htmlspecialchars($row['contribution']) . ' ';
                    } else {
                        echo htmlspecialchars($row['project_part']) . ': pending... ';
                    }
                }
                echo '</dd>';
            } else {
                echo '<p>Member contributions are not available.</p>';
            }

            mysqli_free_result($result);
            ?>
        </dl>

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
                $query = "SELECT m.full_name, f.display_order, f.fact_text
                          FROM member_fun_facts f
                          JOIN team_members m ON f.member_id = m.member_id
                          ORDER BY m.display_order, f.display_order";

                $result = mysqli_query($dbconn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $currentMember = null;
                    $fact1 = '';
                    $fact2 = '';
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($currentMember !== $row['full_name']) {
                            if ($currentMember !== null) {
                                echo '<tr>';
                                echo '<th scope="row">' . htmlspecialchars($currentMember) . '</th>';
                                echo '<td>' . htmlspecialchars($fact1) . '</td>';
                                echo '<td>' . htmlspecialchars($fact2) . '</td>';
                                echo '</tr>';
                            }
                            $currentMember = $row['full_name'];
                            $fact1 = '';
                            $fact2 = '';
                        }

                        if ($row['display_order'] == 1) {
                            $fact1 = $row['fact_text'];
                        } else {
                            $fact2 = $row['fact_text'];
                        }
                    }
                    if ($currentMember !== null) {
                        echo '<tr>';
                        echo '<th scope="row">' . htmlspecialchars($currentMember) . '</th>';
                        echo '<td>' . htmlspecialchars($fact1) . '</td>';
                        echo '<td>' . htmlspecialchars($fact2) . '</td>';
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