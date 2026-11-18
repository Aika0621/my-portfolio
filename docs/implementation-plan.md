# Secure Dynamic Portfolio Project Plan

This project upgrades the existing Aika0621 portfolio instead of replacing its design. Existing portfolio content and page layout will be reused while static content is moved into a PHP and MySQL application.

## Important hosting requirement

GitHub Pages serves static HTML, CSS, and JavaScript. It does not execute PHP or provide a MySQL database. The existing website address can only run the dynamic version if its domain is moved to PHP/MySQL hosting, or if the same address already points to a PHP-capable host. Application code can be prepared in this repository, but it cannot be tested as a working PHP site on GitHub Pages alone.

## Development steps

1. Preserve the existing portfolio and create an implementation branch. Work in small, meaningful, reviewable changes; never manufacture commits or expose credentials.
2. Add the MySQL schema and a safe PDO database connection that reads credentials from environment variables. Keep real secrets outside Git.
3. Set up shared PHP helpers for HTML escaping, session security, CSRF tokens, validation, and common navigation.
4. Convert the existing home, about, skills, projects, resume, and contact pages to PHP while keeping their recognizable design, navigation, and current content.
5. Add secure administrator login/logout, password hashing, session ID regeneration, login throttling, and authenticated page protection.
6. Build a dashboard with counts and management links. Add CRUD screens for profile/settings, skills, projects, education, experience, and achievements.
7. Add validated image uploads with generated filenames, file-content checks, size limits, and execution disabled in the upload directory.
8. Connect the public pages to database content and add the CSRF-protected contact form plus inbox read/unread/delete actions and basic spam controls.
9. Add setup instructions, a safe SQL export with sample content, screenshots, and a testing checklist. Do not put live admin credentials or database passwords in the repository.
10. Test locally and on the chosen PHP/MySQL host: authentication, CRUD, escaping, CSRF rejection, upload validation, responsive pages, and contact messages.
11. Deploy to PHP/MySQL hosting and configure the existing portfolio URL. Verify HTTPS and perform a final end-to-end evaluation.

## What can be automated here

Repository source code, SQL schema, setup documentation, and genuine feature commits can be prepared directly in GitHub. The owner or hosting administrator must still provide/create a PHP/MySQL hosting account, configure secrets and database access there, point the existing URL to that host, and submit private evaluation credentials and video links. Those account actions and external submissions cannot safely be completed from this repository.

## Commit approach

Use one commit for each real, reviewable development step, such as database foundation, shared security helpers, login, and individual content features. Do not split unchanged or arbitrary lines into artificial commits, and do not claim a time interval that was not actually observed.
