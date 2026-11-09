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

This repository contains the full local WordPress project:

- **PHP / WordPress core:** `index.php`, `wp-admin`, `wp-includes`, and related PHP files
- **Themes:** `wp-content/themes/` (custom theme: `team-portfolio-v1-1`)
- **Plugins:** `wp-content/plugins/`
- **Uploads:** `wp-content/uploads/`
- **Database:** `websitedb.sql`

## How to run locally

1. Copy this repository into a local server folder (Laragon `www` or XAMPP `htdocs`).
2. Create a MySQL database named `websitedb`.
3. Import `websitedb.sql` in phpMyAdmin.
4. Copy `wp-config-sample.php` to `wp-config.php` if needed, then set local database user/password.
5. Open the site in the browser and activate **Team Portfolio** if it is not already active.
6. Go to **Settings → Permalinks** and click **Save Changes**.

## Notes

- This repository is submitted for instructor review.
- Some personal details, photos, and project copy may still be placeholders.
- Do not commit live passwords or production database credentials.
