-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2026 at 11:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finova_project2`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(10) UNSIGNED NOT NULL,
  `job_reference` char(6) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb_town` varchar(40) NOT NULL,
  `state` enum('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(254) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `job_reference`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address`, `suburb_town`, `state`, `postcode`, `email`, `phone`, `other_skills`, `status`, `submitted_at`) VALUES
(1, 'FT0002', 'te', 'st', '2005-11-11', 'Male', 'street', 'hawthorn', 'VIC', '3122', 'test@gmail.com', '0466123456', '', 'New', '2026-10-20 03:05:14');

-- --------------------------------------------------------

--
-- Table structure for table `eoi_skills`
--

CREATE TABLE `eoi_skills` (
  `EOInumber` int(10) UNSIGNED NOT NULL,
  `skill` enum('Programming','Microsoft Excel','Leadership','Communication','Organisation','Problem Solving','Teamwork','Time Management') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eoi_skills`
--

INSERT INTO `eoi_skills` (`EOInumber`, `skill`) VALUES
(1, 'Communication'),
(1, 'Problem Solving');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_reference` char(6) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `salary_min` decimal(10,2) NOT NULL,
  `salary_max` decimal(10,2) NOT NULL,
  `salary_currency` char(3) NOT NULL DEFAULT 'AUD',
  `salary_period` varchar(20) NOT NULL DEFAULT 'year',
  `reports_to` varchar(100) NOT NULL,
  `display_order` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_reference`, `title`, `description`, `salary_min`, `salary_max`, `salary_currency`, `salary_period`, `reports_to`, `display_order`) VALUES
('FT0001', 'Data Analyst', 'The Data Analyst supports Finova Technologies by analysing financial and customer data to help the company make better business decisions.', 75000.00, 90000.00, 'AUD', 'year', 'Senior Data Manager', 1),
('FT0002', 'Software Developer', 'The Software Developer designs, develops and maintains secure digital applications used by Finova Technologies customers and employees.', 80000.00, 100000.00, 'AUD', 'year', 'Software Development Manager', 2),
('FT0003', 'Cybersecurity Analyst', 'The Cybersecurity Analyst helps protect Finova Technologies\' systems, customer information and financial data from security threats.', 85000.00, 105000.00, 'AUD', 'year', 'Cybersecurity Manager', 3);

-- --------------------------------------------------------

--
-- Table structure for table `job_items`
--

CREATE TABLE `job_items` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `job_reference` char(6) NOT NULL,
  `item_type` enum('responsibility','essential','preferable') NOT NULL,
  `item_text` text NOT NULL,
  `display_order` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_items`
--

INSERT INTO `job_items` (`item_id`, `job_reference`, `item_type`, `item_text`, `display_order`) VALUES
(1, 'FT0001', 'responsibility', 'Analyse financial and customer data.', 1),
(2, 'FT0001', 'responsibility', 'Create reports and dashboards.', 2),
(3, 'FT0001', 'responsibility', 'Identify trends and patterns in business data.', 3),
(4, 'FT0001', 'responsibility', 'Present findings to managers and team members.', 4),
(5, 'FT0001', 'essential', 'Knowledge of SQL and Excel.', 1),
(6, 'FT0001', 'essential', 'Strong analytical and problem-solving skills.', 2),
(7, 'FT0001', 'essential', 'Good written and verbal communication skills.', 3),
(8, 'FT0001', 'essential', 'Ability to work effectively in a team.', 4),
(9, 'FT0001', 'preferable', 'Experience with Power BI or Tableau.', 1),
(10, 'FT0001', 'preferable', 'Knowledge of financial technology services.', 2),
(11, 'FT0001', 'preferable', 'Basic knowledge of Python.', 3),
(12, 'FT0002', 'responsibility', 'Develop and maintain financial applications.', 1),
(13, 'FT0002', 'responsibility', 'Test and debug software systems.', 2),
(14, 'FT0002', 'responsibility', 'Work with team members to develop new features.', 3),
(15, 'FT0002', 'responsibility', 'Maintain secure and reliable application code.', 4),
(16, 'FT0002', 'essential', 'Knowledge of programming languages such as JavaScript, Java or C#.', 1),
(17, 'FT0002', 'essential', 'Good problem-solving skills.', 2),
(18, 'FT0002', 'essential', 'Understanding of software development principles.', 3),
(19, 'FT0002', 'essential', 'Ability to work effectively in a development team.', 4),
(20, 'FT0002', 'preferable', 'Experience with web application development.', 1),
(21, 'FT0002', 'preferable', 'Knowledge of Git and GitHub.', 2),
(22, 'FT0002', 'preferable', 'Understanding of financial technology systems.', 3),
(23, 'FT0003', 'responsibility', 'Monitor systems for possible security threats.', 1),
(24, 'FT0003', 'responsibility', 'Identify and respond to security incidents.', 2),
(25, 'FT0003', 'responsibility', 'Protect customer and financial information.', 3),
(26, 'FT0003', 'responsibility', 'Assist with security reviews and risk assessments.', 4),
(27, 'FT0003', 'essential', 'Knowledge of cybersecurity principles.', 1),
(28, 'FT0003', 'essential', 'Understanding of network security.', 2),
(29, 'FT0003', 'essential', 'Strong attention to detail.', 3),
(30, 'FT0003', 'essential', 'Good analytical and problem-solving skills.', 4),
(31, 'FT0003', 'preferable', 'Knowledge of cloud security.', 1),
(32, 'FT0003', 'preferable', 'Experience using security monitoring tools.', 2),
(33, 'FT0003', 'preferable', 'Understanding of financial data security.', 3);

-- --------------------------------------------------------

--
-- Table structure for table `member_contributions`
--

CREATE TABLE `member_contributions` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `project_part` enum('Part 1','Part 2') NOT NULL,
  `contribution` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_contributions`
--

INSERT INTO `member_contributions` (`member_id`, `project_part`, `contribution`) VALUES
(1, 'Part 1', 'Responsible for the About the Team Page (about.html), as well as creating the GitHub repository , Pages and user stories and tasks in Jira and maintaining them.'),
(1, 'Part 2', 'Developed the HR login, logout, session authentication, built shared header/nav/footer includes, created settings.php, and converted the About page to load content from the database (about.php)'),
(2, 'Part 1', 'Responsible for the homepage (index.html).'),
(2, 'Part 2', 'I converted the homepage to PHP and built the HR application management features, including filtering, sorting, status updates, and deletion.'),
(3, 'Part 1', 'Responsible for the Apply page (apply.html).'),
(3, 'Part 2', 'Forked the former Apply.html into Apply.php. Developed the entirety of Process_eoi.php'),
(4, 'Part 1', 'Responsible for the Position Description Page (jobs.html).'),
(4, 'Part 2', 'I was developing the dynamic job listings and job search functionality using PHP and MYSQL.');

-- --------------------------------------------------------

--
-- Table structure for table `member_fun_facts`
--

CREATE TABLE `member_fun_facts` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `display_order` smallint(5) UNSIGNED NOT NULL,
  `fact_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_fun_facts`
--

INSERT INTO `member_fun_facts` (`member_id`, `display_order`, `fact_text`) VALUES
(1, 1, 'My father has four older brothers.'),
(1, 2, 'I was born in China.'),
(2, 1, 'I am good at all kinds of video games.'),
(2, 2, 'I am skilled at playing the guitar and have won awards in major competitions.'),
(3, 1, 'I really enjoy video games, particularly Overwatch.'),
(3, 2, 'Penguins are my favourite animals… did you know that they have knees o.O'),
(4, 1, 'I can speak 4 languages.'),
(4, 2, 'I am a big fan of Manchester City since 2015.');

-- --------------------------------------------------------

--
-- Table structure for table `member_quotes`
--

CREATE TABLE `member_quotes` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `original_quote` text NOT NULL,
  `english_translation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_quotes`
--

INSERT INTO `member_quotes` (`member_id`, `original_quote`, `english_translation`) VALUES
(1, '“千里之行，始于足下”', 'A journey of a thousand miles begins with a single step.'),
(2, '“车到山前必有路”', 'Things will eventually sort themselves out.'),
(3, '“Every problem has a solution.”', 'The philosophy that you can overcome any dilemma, given that you have enough time.'),
(4, '“ຖ້າເຈົ້າມັກໃຫ້ສິ່ງທິ່ງິ່າຍ ເຈົ້າຈະພົບກັບຄວາມຫຍ້ງຍາກ”', 'If you like to have things easy, you will have difficulties.');

-- --------------------------------------------------------

--
-- Table structure for table `team_info`
--

CREATE TABLE `team_info` (
  `team_id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `tutorial_time` varchar(100) NOT NULL,
  `tutor_name` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `photo_alt` varchar(255) NOT NULL,
  `photo_caption` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_info`
--

INSERT INTO `team_info` (`team_id`, `group_name`, `tutorial_time`, `tutor_name`, `introduction`, `photo_path`, `photo_alt`, `photo_caption`) VALUES
(1, 'Rahul-wed-1430-G04-The brain cells', 'Wednesday 2:30–4:30 P.M.', 'Rahul Raghavan', 'Meet the students behind Finova Technologies. We are a group of four Swinburne students working together on the COS10026 Web Technology Project.', 'images/group.jpg', 'Group photo of the Finova Technologies student team.', 'The brain cells team — COS10026 Group G04, Semester 2, 2026.');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `display_order` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`member_id`, `team_id`, `full_name`, `student_id`, `display_order`) VALUES
(1, 1, 'Jiajin Zhang', '105967281', 1),
(2, 1, 'Zhenghao Sun', '105965706', 2),
(3, 1, 'Andy Nguyen', '106748733', 3),
(4, 1, 'Thaanthep Douangchaleun', '105313376', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$kYXheQjQWnT200ooDDG0xO/eFVC5n8SS/H8EI8MTeyeHUJrS6QytO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`),
  ADD KEY `idx_eoi_job` (`job_reference`),
  ADD KEY `idx_eoi_first_last` (`first_name`,`last_name`),
  ADD KEY `idx_eoi_last` (`last_name`),
  ADD KEY `idx_eoi_status` (`status`);

--
-- Indexes for table `eoi_skills`
--
ALTER TABLE `eoi_skills`
  ADD PRIMARY KEY (`EOInumber`,`skill`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_reference`);

--
-- Indexes for table `job_items`
--
ALTER TABLE `job_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `uq_job_item_order` (`job_reference`,`item_type`,`display_order`);

--
-- Indexes for table `member_contributions`
--
ALTER TABLE `member_contributions`
  ADD PRIMARY KEY (`member_id`,`project_part`);

--
-- Indexes for table `member_fun_facts`
--
ALTER TABLE `member_fun_facts`
  ADD PRIMARY KEY (`member_id`,`display_order`);

--
-- Indexes for table `member_quotes`
--
ALTER TABLE `member_quotes`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `team_info`
--
ALTER TABLE `team_info`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `uq_member_student_id` (`student_id`),
  ADD KEY `fk_members_team` (`team_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_items`
--
ALTER TABLE `job_items`
  MODIFY `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eoi`
--
ALTER TABLE `eoi`
  ADD CONSTRAINT `fk_eoi_job` FOREIGN KEY (`job_reference`) REFERENCES `jobs` (`job_reference`);

--
-- Constraints for table `eoi_skills`
--
ALTER TABLE `eoi_skills`
  ADD CONSTRAINT `fk_eoi_skills_eoi` FOREIGN KEY (`EOInumber`) REFERENCES `eoi` (`EOInumber`) ON DELETE CASCADE;

--
-- Constraints for table `job_items`
--
ALTER TABLE `job_items`
  ADD CONSTRAINT `fk_job_items_job` FOREIGN KEY (`job_reference`) REFERENCES `jobs` (`job_reference`) ON DELETE CASCADE;

--
-- Constraints for table `member_contributions`
--
ALTER TABLE `member_contributions`
  ADD CONSTRAINT `fk_contributions_member` FOREIGN KEY (`member_id`) REFERENCES `team_members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `member_fun_facts`
--
ALTER TABLE `member_fun_facts`
  ADD CONSTRAINT `fk_facts_member` FOREIGN KEY (`member_id`) REFERENCES `team_members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `member_quotes`
--
ALTER TABLE `member_quotes`
  ADD CONSTRAINT `fk_quotes_member` FOREIGN KEY (`member_id`) REFERENCES `team_members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `fk_members_team` FOREIGN KEY (`team_id`) REFERENCES `team_info` (`team_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
