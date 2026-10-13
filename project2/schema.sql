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
