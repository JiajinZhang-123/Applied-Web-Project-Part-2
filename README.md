# Finova Technologies — COS10026 Project Part 2

A recruitment website for the fictional company **Finova Technologies**, built for COS10026
Web Technology Project, Part 2 (Group G04, "The brain cells").

Part 1 delivered a static HTML/CSS site. Part 2 turns it into a working PHP + MySQL application:
pages are rendered from the database, the application form is validated on the server, and an
authenticated HR area manages real applications.

| Member | Student ID |
|---|---|
| Jiajin Zhang | 105967281 |
| Zhenghao Sun | 105965706 |
| Andy Nguyen | 106748733 |
| Thaanthep Douangchaleun | 105313376 |

---

## 1. Start

### Requirements

- XAMPP (Apache + MySQL/MariaDB) or any PHP 8.x + MySQL 5.7/MariaDB 10.4 setup
- A web browser

### Steps

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Copy the `project2` folder into the web root (e.g. `C:\xampp\htdocs\project2`).
3. Open phpMyAdmin (`http://localhost/phpmyadmin`).
4. Create a database named **`finova_project2`** with collation **`utf8mb4_general_ci`**
   (skip this step if the database already exists).
5. **Click the `finova_project2` database in the left panel so it is selected.**
6. Go to the **Import** tab, choose `project2/finova_project2.sql`, keep the format as **SQL**,
   and click **Go / Execute**.
7. Open the site: `http://localhost/project2/index.php`.
8. Log in to the HR area with username **`admin`** and password **`admin`**
   (`http://localhost/project2/login.php`).

---
