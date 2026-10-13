-- Run after selecting your database and installing jobs.
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

