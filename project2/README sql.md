# Finova Project 2 Database Reference

This package provides a shared database starting point for the team. PHP pages still need to be implemented. Jira references in the seed data describe Part 1 contributions only; Part 2 does not require Jira work.

## Package Contents

- `finova_project2.sql`: Complete database creation, table definitions, and initial data.
- `schema.sql`: Table definitions without initial data.
- `eoi_structure.sql`: Definitions for the two EOI tables, for automatic table creation in `process_eoi.php`.
- `README.md`: Import instructions, field mapping, and integration guidance.

## Import and Connection Settings

Start MySQL in XAMPP, open phpMyAdmin, and import `finova_project2.sql`. It creates and selects `finova_project2` using utf8mb4.

Import into a new database. There are no DROP statements or operations that reset existing data. Seed INSERT statements are intended for a single import; repeated imports produce duplicate-key errors. `IF NOT EXISTS` does not upgrade existing table definitions.

| Setting | Value |
|---|---|
| Host | `localhost` |
| User | `root` |
| Password | Empty string |
| Database | `finova_project2` |

The HR login is separate: username `admin`, password `admin`. The database stores a bcrypt hash. Use PHP `password_verify()` to check the password, rather than comparing it directly with the stored hash.

## Tables and Relationships

| Table | Purpose |
|---|---|
| jobs | Three existing jobs: reference, title, description, salary range, and reporting line |
| job_items | Ordered responsibilities, essential requirements, and preferable requirements |
| eoi | Application records, automatic EOInumber, and status |
| eoi_skills | One row per selected skill; composite primary key prevents duplicates |
| users | HR usernames and password hashes |
| team_info | Group, tutorial, tutor, introduction, and photo metadata |
| team_members | Names, student IDs, and display order |
| member_contributions | Part 1 and Part 2 contributions for each member |
| member_quotes | Original quotes and English translations |
| member_fun_facts | Member facts and display order |

Relationships:

- `jobs` to `job_items`.
- `jobs` to `eoi` to `eoi_skills`.
- `team_info` to `team_members` to contributions, quotes, and fun facts.

Use `display_order` for ordered content. Salaries use two numeric fields, formatted as a range in PHP. Job list items are plain text, not complete HTML blocks. Static About headings may remain in PHP; group and member content should come from the database.

Part 2 contributions initially contain NULL. Display a pending message and update them after actual completion. Do not present proposed responsibilities as completed work. The fourth member's name uses `Thaanthep Douangchaleun`, matching the Part 1 contributions section; confirm the spelling with that member. Move the team photo to `images/group.jpg` in the final website.

## EOI Fields and Form Mapping

| HTML name | Database field | Type and handling |
|---|---|---|
| None | EOInumber | INT UNSIGNED AUTO_INCREMENT; retrieve the generated ID after insertion |
| jobReferenceNumber | job_reference | CHAR(6); must match an existing job |
| firstName | first_name | VARCHAR(20) |
| lastName | last_name | VARCHAR(20) |
| dateOfBirth | date_of_birth | DATE; validate dd/mm/yyyy and convert to yyyy-mm-dd |
| Gender | gender | Male / Female / Other |
| streetAddress | street_address | VARCHAR(40) |
| suburbTown | suburb_town | VARCHAR(40) |
| State | state | VIC / NSW / QLD / NT / WA / SA / TAS / ACT |
| Postcode | postcode | CHAR(4); preserves leading zeros such as 0800 |
| email | email | VARCHAR(254); validate address and length in PHP |
| phone | phone | VARCHAR(12); preserves leading zeros; validate 8-12 digits |
| skills[] | eoi_skills.skill | Store each selected skill as a separate row linked to EOInumber |
| otherSkills | other_skills | TEXT NULL; optional |
| Not accepted from applicants | status | New / Current / Final; defaults to New |
| None | submitted_at | TIMESTAMP; defaults to submission time |

HTML field names retain their original capitalization. Map them explicitly to database fields. If the team renames fields, update both the form and processing code.

## PHP Integration Guidelines

1. Check the request method and required fields in `process_eoi.php`. Validate on the server independently of HTML required and pattern attributes.
2. For automatic EOI table creation, execute both CREATE statements in `eoi_structure.sql` after selecting the database. The `jobs` table must already exist. Do not execute the complete seed SQL on each submission.
3. Save the EOI and skills in one transaction: insert EOI, retrieve EOInumber, insert skills, and COMMIT. ROLLBACK on failure to prevent partial records.
4. Use the default status. Never accept management status from an applicant's POST data.
5. Use prepared statements. Store validated, normalized text and escape it when displaying HTML. HTML escaping does not replace SQL parameter binding. Apply trim, stripslashes, and HTML escaping at appropriate stages without repeated escaping.
6. Allowed skills match the eight existing checkboxes. PHP must enforce requirements such as selecting at least one skill; the database cannot guarantee a child record exists.
7. NOT NULL does not reject empty strings or enforce full email, phone, name, or date rules. PHP validation is required even in strict SQL mode.
8. Confirm age limits, state/postcode matching, required skills, and conditional Other Skills rules against the original Part 1 specification. Current HTML patterns are not a substitute for the original requirements.
9. Deleting an EOI automatically deletes its skill records. Jobs with existing EOIs cannot be deleted. Authenticate every management operation and protect POST requests for deletion and status changes.
10. Use an allowlist for sorting columns. Do not insert user input directly into ORDER BY. Skill joins can return multiple rows per application; query EOIs separately or aggregate skills for display.

## Final Submission

This is a proposed design for team agreement. Keep runtime CREATE statements consistent with the exported SQL. Export the completed database as `finova_project2.sql` and check the full website in a fresh deployment directory. This package does not replace PHP integration testing.
