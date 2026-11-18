# Secure Dynamic Personal Portfolio

PHP and MySQL upgrade of Aika's existing portfolio. Work is in progress on the `feature/php-mysql-foundation` branch; the static `main` branch is not replaced.

## Requirements

- XAMPP with Apache, PHP 8+, MySQL or MariaDB
- PHP PDO MySQL extension enabled

## Local setup

1. Copy the project folder into `C:\xampp\htdocs\my-portfolio`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`, create a database named `aika_portfolio`, then import `database/portfolio.sql`.
4. Local database defaults are host `127.0.0.1`, database `aika_portfolio`, user `root`, and an empty password. On another host, set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` in the server environment. Never commit actual database credentials.
5. Create an admin password hash in a terminal using PHP: `php -r "echo password_hash('replace-this-with-your-own-password', PASSWORD_DEFAULT), PHP_EOL;"`. In phpMyAdmin insert the hash: `INSERT INTO admins (email, password_hash) VALUES ('your-email@example.com', 'paste-generated-hash-here');`. Use your own email and password, and do not store the plain password in GitHub.
6. Visit `http://localhost/my-portfolio/index.php`. Admin login is at `http://localhost/my-portfolio/admin/login.php`.

## Features implemented

- PHP public home, About, Skills, Projects, Resume, and Contact pages
- MySQL database schema and sample skills/projects/profile
- PDO connection and prepared statements
- Escaped database output, CSRF tokens, session protection, and password verification
- Admin dashboard with project, skill, experience, and message counts
- Profile, skills, projects, education, experience, achievements, and basic website-title management
- Project and profile image upload checks for real JPG, PNG, or GIF files and file size
- Contact form and admin message read/unread/delete functions
- Upload folder denies PHP execution in Apache

## Important hosting note

GitHub Pages does not run PHP or MySQL. Use XAMPP to test locally and PHP/MySQL hosting to deploy a working public site. Configure HTTPS on a live host. Check the hosting provider's Apache configuration before relying on `.htaccess` upload restrictions.

Before submission, test login and logout, CRUD add/edit/delete, contact messages, bad CSRF tokens, invalid images, narrow mobile layout, and data remaining after restarting Apache/MySQL. Screenshots, demonstration video, live host deployment, and private LMS credentials must be prepared by the student.
