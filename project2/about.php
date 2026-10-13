<?php

require_once 'settings.php';

// connect databse
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$dbconn) {
    echo '<p>Unable to connect to the database.</p>';
}

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

        <article class="quote-card">
            <p class="quote-original">&ldquo;千里之行，始于足下&rdquo;</p>
            <p class="quote-author">Jiajin Zhang</p>
            <p class="quote-english">
                English: A journey of a thousand miles begins with a
                single step.
            </p>
        </article>

        <article class="quote-card">
            <p class="quote-original">&ldquo;车到山前必有路&rdquo;</p>
            <p class="quote-author">Zhenghao Sun</p>
            <p class="quote-english">
                English: Things will eventually sort themselves out.
            </p>
        </article>

        <article class="quote-card">
            <p class="quote-original">&ldquo;Every problem has a solution.&rdquo;</p>
            <p class="quote-author">Andy Nguyen</p>
            <p class="quote-english">
                English: The philosophy that you can overcome any
                dilemma, given that you have enough time.
            </p>
        </article>

        <article class="quote-card">
            <p class="quote-original">
                &ldquo;ຖ້າເຈົ້າມັກໃຫ້ສິ່ງທິ່ງິ່າຍ
                ເຈົ້າຈະພົບກັບຄວາມຫຍ້ງຍາກ&rdquo;
            </p>
            <p class="quote-author">Thaanthep Douangchaleun</p>
            <p class="quote-english">
                English: If you like to have things easy, you will
                have difficulties.
            </p>
        </article>

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
                <tr>
                    <th scope="row">Jiajin Zhang</th>
                    <td>My father has four older brothers.</td>
                    <td>I was born in China.</td>
                </tr>
                <tr>
                    <th scope="row">Zhenghao Sun</th>
                    <td>I am good at all kinds of video games.</td>
                    <td>
                        I am skilled at playing the guitar and
                        have won awards in major competitions.
                    </td>
                </tr>
                <tr>
                    <th scope="row">Andy Nguyen</th>
                    <td>
                        I really enjoy video games, particularly
                        Overwatch.
                    </td>
                    <td>
                        Penguins are my favourite animals&hellip;
                        did you know that they have knees o.O
                    </td>
                </tr>
                <tr>
                    <th scope="row">Thanathep Douangchaleun</th>
                    <td>I can speak 4 languages.</td>
                    <td>I am a big fan of Manchester City since 2015.</td>
                </tr>
            </tbody>
        </table>

    </section>

<?php include 'footer.inc'; ?>