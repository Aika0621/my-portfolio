# Secure Dynamic Personal Portfolio

PHP and MySQL upgrade of the existing Aika0621 portfolio. The existing HTML pages are kept while the PHP home page and admin foundation are developed.

## Local requirements

- XAMPP with Apache, PHP 8+, and MySQL/MariaDB
- PHP PDO MySQL extension enabled

## Setup with XAMPP

1. Copy or clone the repository into `C:\xampp\htdocs\my-portfolio`.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin` and import `database/portfolio.sql`.
4. The local database defaults are host `127.0.0.1`, database `aika_portfolio`, user `root`, and an empty password. Update `includes/db.php` environment settings through `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` on a host that uses different credentials. Never commit real passwords.
5. Create the first admin password hash with PHP and insert the admin row into the `admins` table. Example command: `php -r "echo password_hash('choose-a-private-password', PASSWORD_DEFAULT), PHP_EOL;"`. Insert the resulting hash, not the password, with `INSERT INTO admins (email, password_hash) VALUES ('your-email@example.com', 'paste-generated-hash-here');` in phpMyAdmin. Do not use the example password.
6. Visit `http://localhost/my-portfolio/index.php`. Login at `http://localhost/my-portfolio/admin/login.php`.

The PHP files are not executable on GitHub Pages. Use XAMPP locally and a PHP/MySQL host for deployment.

## Current implementation

- MySQL schema and sample skills, projects, and profile
- PDO database connection with prepared-statement defaults and safe error output
- Dynamic PHP portfolio home page with escaped database content
- Admin login/logout foundation with password verification, CSRF tokens, session ID regeneration, and session-based attempt limits
- Initial dashboard statistics
- Upload folder Apache rule denying PHP execution

Content CRUD, contact management, secure upload handling, and deployment documentation are planned next.
