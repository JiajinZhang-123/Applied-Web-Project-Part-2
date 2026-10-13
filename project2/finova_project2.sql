-- Import ONCE into a new database. No DROP statements; repeated seed import fails on duplicates.
SET NAMES utf8mb4;
-- Reference schema for COS10026 Applied Web Project Part 2.
-- MySQL 8 / MariaDB 10.4; UTF-8 including Chinese and Lao content.
CREATE DATABASE IF NOT EXISTS finova_project2
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE finova_project2;

CREATE TABLE IF NOT EXISTS jobs (
  job_reference CHAR(6) NOT NULL,
  title VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  salary_min DECIMAL(10,2) NOT NULL,
  salary_max DECIMAL(10,2) NOT NULL,
  salary_currency CHAR(3) NOT NULL DEFAULT 'AUD',
  salary_period VARCHAR(20) NOT NULL DEFAULT 'year',
  reports_to VARCHAR(100) NOT NULL,
  display_order SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (job_reference)
) ENGINE=InnoDB;

-- Responsibilities and requirement lists: one item per row, preserving order.
CREATE TABLE IF NOT EXISTS job_items (
  item_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_reference CHAR(6) NOT NULL,
  item_type ENUM('responsibility','essential','preferable') NOT NULL,
  item_text TEXT NOT NULL,
  display_order SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (item_id),
  UNIQUE KEY uq_job_item_order (job_reference, item_type, display_order),
  CONSTRAINT fk_job_items_job FOREIGN KEY (job_reference)
    REFERENCES jobs (job_reference) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_reference CHAR(6) NOT NULL,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  date_of_birth DATE NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  street_address VARCHAR(40) NOT NULL,
  suburb_town VARCHAR(40) NOT NULL,
  state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode CHAR(4) NOT NULL,
  email VARCHAR(254) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  other_skills TEXT NULL,
  status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (EOInumber),
  KEY idx_eoi_job (job_reference),
  KEY idx_eoi_first_last (first_name, last_name),
  KEY idx_eoi_last (last_name),
  KEY idx_eoi_status (status),
  CONSTRAINT fk_eoi_job FOREIGN KEY (job_reference)
    REFERENCES jobs (job_reference) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Selected checkboxes; no comma-separated list and no duplicated skills.
CREATE TABLE IF NOT EXISTS eoi_skills (
  EOInumber INT UNSIGNED NOT NULL,
  skill ENUM('Programming','Microsoft Excel','Leadership','Communication',
    'Organisation','Problem Solving','Teamwork','Time Management') NOT NULL,
  PRIMARY KEY (EOInumber, skill),
  CONSTRAINT fk_eoi_skills_eoi FOREIGN KEY (EOInumber)
    REFERENCES eoi (EOInumber) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
  user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS team_info (
  team_id INT UNSIGNED NOT NULL,
  group_name VARCHAR(150) NOT NULL,
  tutorial_time VARCHAR(100) NOT NULL,
  tutor_name VARCHAR(100) NOT NULL,
  introduction TEXT NOT NULL,
  photo_path VARCHAR(255) NOT NULL,
  photo_alt VARCHAR(255) NOT NULL,
  photo_caption VARCHAR(255) NOT NULL,
  PRIMARY KEY (team_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS team_members (
  member_id INT UNSIGNED NOT NULL,
  team_id INT UNSIGNED NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  student_id VARCHAR(20) NOT NULL,
  display_order SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (member_id),
  UNIQUE KEY uq_member_student_id (student_id),
  CONSTRAINT fk_members_team FOREIGN KEY (team_id) REFERENCES team_info (team_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS member_contributions (
  member_id INT UNSIGNED NOT NULL,
  project_part ENUM('Part 1','Part 2') NOT NULL,
  contribution TEXT NULL,
  PRIMARY KEY (member_id, project_part),
  CONSTRAINT fk_contributions_member FOREIGN KEY (member_id)
    REFERENCES team_members (member_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS member_quotes (
  member_id INT UNSIGNED NOT NULL,
  original_quote TEXT NOT NULL,
  english_translation TEXT NOT NULL,
  PRIMARY KEY (member_id),
  CONSTRAINT fk_quotes_member FOREIGN KEY (member_id)
    REFERENCES team_members (member_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS member_fun_facts (
  member_id INT UNSIGNED NOT NULL,
  display_order SMALLINT UNSIGNED NOT NULL,
  fact_text TEXT NOT NULL,
  PRIMARY KEY (member_id, display_order),
  CONSTRAINT fk_facts_member FOREIGN KEY (member_id)
    REFERENCES team_members (member_id) ON DELETE CASCADE
) ENGINE=InnoDB;

START TRANSACTION;
INSERT INTO jobs (job_reference, title, description, salary_min, salary_max, reports_to, display_order) VALUES ('FT0001', 'Data Analyst', 'The Data Analyst supports Finova Technologies by analysing financial and customer data to help the company make better business decisions.', 75000, 90000, 'Senior Data Manager', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'responsibility', 'Analyse financial and customer data.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'responsibility', 'Create reports and dashboards.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'responsibility', 'Identify trends and patterns in business data.', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'responsibility', 'Present findings to managers and team members.', 4);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'essential', 'Knowledge of SQL and Excel.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'essential', 'Strong analytical and problem-solving skills.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'essential', 'Good written and verbal communication skills.', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'essential', 'Ability to work effectively in a team.', 4);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'preferable', 'Experience with Power BI or Tableau.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'preferable', 'Knowledge of financial technology services.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0001', 'preferable', 'Basic knowledge of Python.', 3);
INSERT INTO jobs (job_reference, title, description, salary_min, salary_max, reports_to, display_order) VALUES ('FT0002', 'Software Developer', 'The Software Developer designs, develops and maintains secure digital applications used by Finova Technologies customers and employees.', 80000, 100000, 'Software Development Manager', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'responsibility', 'Develop and maintain financial applications.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'responsibility', 'Test and debug software systems.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'responsibility', 'Work with team members to develop new features.', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'responsibility', 'Maintain secure and reliable application code.', 4);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'essential', 'Knowledge of programming languages such as JavaScript, Java or C#.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'essential', 'Good problem-solving skills.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'essential', 'Understanding of software development principles.', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'essential', 'Ability to work effectively in a development team.', 4);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'preferable', 'Experience with web application development.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'preferable', 'Knowledge of Git and GitHub.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0002', 'preferable', 'Understanding of financial technology systems.', 3);
INSERT INTO jobs (job_reference, title, description, salary_min, salary_max, reports_to, display_order) VALUES ('FT0003', 'Cybersecurity Analyst', 'The Cybersecurity Analyst helps protect Finova Technologies'' systems, customer information and financial data from security threats.', 85000, 105000, 'Cybersecurity Manager', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'responsibility', 'Monitor systems for possible security threats.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'responsibility', 'Identify and respond to security incidents.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'responsibility', 'Protect customer and financial information.', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'responsibility', 'Assist with security reviews and risk assessments.', 4);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'essential', 'Knowledge of cybersecurity principles.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'essential', 'Understanding of network security.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'essential', 'Strong attention to detail.', 3);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'essential', 'Good analytical and problem-solving skills.', 4);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'preferable', 'Knowledge of cloud security.', 1);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'preferable', 'Experience using security monitoring tools.', 2);
INSERT INTO job_items (job_reference, item_type, item_text, display_order) VALUES ('FT0003', 'preferable', 'Understanding of financial data security.', 3);
INSERT INTO team_info (team_id, group_name, tutorial_time, tutor_name, introduction, photo_path, photo_alt, photo_caption) VALUES (1, 'Rahul-wed-1430-G04-The brain cells', 'Wednesday 2:30–4:30 P.M.', 'Rahul Raghavan', 'Meet the students behind Finova Technologies. We are a group of four Swinburne students working together on the COS10026 Web Technology Project.', 'images/group.jpg', 'Group photo of the Finova Technologies student team.', 'The brain cells team — COS10026 Group G04, Semester 2, 2026.');
INSERT INTO team_members (member_id, team_id, full_name, student_id, display_order) VALUES (1, 1, 'Jiajin Zhang', '105967281', 1);
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (1, 'Part 1', 'Responsible for the About the Team Page (about.html), as well as creating the GitHub repository , Pages and user stories and tasks in Jira and maintaining them.');
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (1, 'Part 2', NULL);
INSERT INTO member_quotes (member_id, original_quote, english_translation) VALUES (1, '“千里之行，始于足下”', 'A journey of a thousand miles begins with a single step.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (1, 1, 'My father has four older brothers.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (1, 2, 'I was born in China.');
INSERT INTO team_members (member_id, team_id, full_name, student_id, display_order) VALUES (2, 1, 'Zhenghao Sun', '105965706', 2);
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (2, 'Part 1', 'Responsible for the homepage (index.html).');
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (2, 'Part 2', NULL);
INSERT INTO member_quotes (member_id, original_quote, english_translation) VALUES (2, '“车到山前必有路”', 'Things will eventually sort themselves out.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (2, 1, 'I am good at all kinds of video games.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (2, 2, 'I am skilled at playing the guitar and have won awards in major competitions.');
INSERT INTO team_members (member_id, team_id, full_name, student_id, display_order) VALUES (3, 1, 'Andy Nguyen', '106748733', 3);
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (3, 'Part 1', 'Responsible for the Apply page (apply.html).');
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (3, 'Part 2', NULL);
INSERT INTO member_quotes (member_id, original_quote, english_translation) VALUES (3, '“Every problem has a solution.”', 'The philosophy that you can overcome any dilemma, given that you have enough time.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (3, 1, 'I really enjoy video games, particularly Overwatch.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (3, 2, 'Penguins are my favourite animals… did you know that they have knees o.O');
INSERT INTO team_members (member_id, team_id, full_name, student_id, display_order) VALUES (4, 1, 'Thaanthep Douangchaleun', '105313376', 4);
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (4, 'Part 1', 'Responsible for the Position Description Page (jobs.html).');
INSERT INTO member_contributions (member_id, project_part, contribution) VALUES (4, 'Part 2', NULL);
INSERT INTO member_quotes (member_id, original_quote, english_translation) VALUES (4, '“ຖ້າເຈົ້າມັກໃຫ້ສິ່ງທິ່ງິ່າຍ ເຈົ້າຈະພົບກັບຄວາມຫຍ້ງຍາກ”', 'If you like to have things easy, you will have difficulties.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (4, 1, 'I can speak 4 languages.');
INSERT INTO member_fun_facts (member_id, display_order, fact_text) VALUES (4, 2, 'I am a big fan of Manchester City since 2015.');
INSERT INTO users (username, password_hash) VALUES ('admin', '$2y$10$kYXheQjQWnT200ooDDG0xO/eFVC5n8SS/H8EI8MTeyeHUJrS6QytO');
COMMIT;
