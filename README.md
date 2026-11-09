# wp-group_portfolio
BSIT student team portfolio built with WordPress. Includes a group site and individual pages for Chloe, John Bryan, and Keziyah (The Creative Stack, Opol Community College).


# The Creative Stack — WordPress Team Portfolio

Academic WordPress project by BS Information Technology students of **Opol Community College**.

This site presents one group portfolio and three individual student portfolios under a custom theme named **Team Portfolio**.

## Team members

- Chloe Lou D. Brotonel
- John Bryan T. Mandario
- Keziyah Jarelle C. Lopez

## About the project

The website is branded **The Creative Stack**. It shows who we are as a team, our skills, selected academic projects, and a short journey timeline. Each member also has a personal portfolio page (About, Education, Skills, Projects, Experience, Achievements, and Contact).

## Features

- Group homepage with team cards linking to each member
- Individual portfolio pages for Chloe, John Bryan, and Keziyah
- Selected projects: OPCR & IPCR Monitoring System, Hotel Management System, and Quiz Game
- Responsive layout with light/dark theme toggle
- Contact section (opens the visitor’s email app)

## Tech stack

- WordPress
- Custom theme: `team-portfolio-v1-1`
- PHP, HTML, CSS, JavaScript
- Local development with Laragon

## Repository contents

- **Theme / PHP:** WordPress custom theme files (`functions.php`, `style.css`, `templates`, `assets`, and related folders)
- **Database:** `websitedb.sql` (export of the local WordPress database `websitedb`)
- **Config sample:** `wp-config-sample.php` (copy to `wp-config.php` for local setup)

## How to run locally

1. Install WordPress on Laragon or XAMPP.
2. Copy this theme into `wp-content/themes/team-portfolio-v1-1`.
3. Create a MySQL database named `websitedb`.
4. Import `websitedb.sql` in phpMyAdmin (or: `mysql -u root websitedb < websitedb.sql`).
5. Copy `wp-config-sample.php` to `wp-config.php` and set your local database user/password.
6. Activate the **Team Portfolio** theme.
7. Go to **Settings → Permalinks** and click **Save Changes**.

## Notes

- This repository is submitted for instructor review.
- Some personal details, photos, and project copy may still be placeholders.
- Do not commit live passwords or production database credentials.
