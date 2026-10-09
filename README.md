# Complete Point-of-Sale System

A complete CodeIgniter 4 POS application for the IT0049 Web System Technologies midterm project. It includes authenticated product, customer, and staff management; safe image uploads; transactional sales; stock control; and sales history.

## Features

- Secure staff login and logout with session regeneration
- Authentication filter protecting every management page
- Product CRUD with archive/restore, stock tracking, and prepared images
- Customer full CRUD
- Staff full CRUD with hashed passwords and prepared avatars
- Record Sale workflow with an optional customer
- Atomic stock deduction and sale creation inside one database transaction
- Overselling protection using a conditional database update
- Sales history with product, customer, staff, quantity, total, and date
- CSRF protection, server-side validation, escaped output, random upload filenames, MIME checks, and a 2 MB image limit
- Responsive interface for desktop and mobile browsers
- Version-controlled migration, seeder, and SQL schema

## Requirements

- PHP 8.2 or newer
- Composer 2.0.14 or newer
- MySQL 8+ or MariaDB 10.4+
- PHP extensions: `intl`, `mbstring`, `mysqli`, `fileinfo`, and either `gd` or `imagick`

For XAMPP, enable these extensions in `php.ini` by removing the leading semicolon from their `extension=` lines, then restart Apache.

## Quick Setup

1. Extract or clone this project.
2. Open a terminal inside the `pos-system` directory.
3. Install dependencies:

   ```bash
   composer install
   ```

   Composer also fills in the standard CodeIgniter starter configuration files that are not project-specific.

4. Copy `env` to `.env`:

   Windows Command Prompt:

   ```bat
   copy env .env
   ```

   PowerShell, macOS, or Linux:

   ```bash
   cp env .env
   ```

5. Create a MySQL database named `pos_system`:

   ```sql
   CREATE DATABASE pos_system CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```

6. Edit `.env` and set your database username, password, host, and port. The defaults work for many XAMPP installations where MySQL uses `root` with no password.
7. Create the tables and sample data:

   ```bash
   php spark migrate --all
   php spark db:seed PosSeeder
   ```

8. Start the development server:

   ```bash
   php spark serve
   ```

9. Open <http://localhost:8080>.

### Demo Login

- Username: `admin`
- Password: `Admin123!`

Change this password immediately from the Staff page before deployment.

## Alternative Database Import

The preferred method is the migration and seeder above. A raw schema is also included at `database/schema.sql` for the submission requirement. It creates the tables but intentionally does not insert the administrator because credentials should be hashed at setup time. Run the seeder after importing the schema:

```bash
php spark db:seed PosSeeder
```

Do not run both the migration and the SQL schema against the same populated database.

## Project Structure

```text
app/
├── Config/                 Routes, database, CSRF, and filters
├── Controllers/            Authentication and feature controllers
├── Database/
│   ├── Migrations/         Relational schema
│   └── Seeds/              Admin and sample records
├── Filters/                Login protection
├── Libraries/              Secure image preparation
├── Models/                 Product, customer, user, and sale models
└── Views/                  Responsive UI templates
database/schema.sql         Optional raw SQL export
public/                     Public entry point, CSS, and uploads
scripts/                    Composer setup helper
writable/                   CodeIgniter runtime data
```

## Important Design Decisions

### Sale consistency

Recording a sale starts a database transaction. The stock update is conditional:

```sql
UPDATE products
SET stock_quantity = stock_quantity - ?
WHERE id = ? AND is_active = 1 AND stock_quantity >= ?;
```

If no row is updated, the requested stock is unavailable and the entire operation is rolled back. The sale price always comes from the database rather than the browser.

### Historical records

Products are archived instead of deleted so sales history remains readable. Customers and staff with related sales cannot be deleted. The current signed-in staff member cannot delete their own account.

### Upload safety

Uploads are checked using the server-detected MIME type, restricted to JPG/PNG/WebP, limited to 2 MB, renamed randomly, cropped to a display-ready square, and stored under `public/uploads`. Server-side script files are denied in the upload directory.

## GitHub Submission

Create an empty repository on GitHub, then run:

```bash
git init
git add .
git commit -m "Complete CodeIgniter POS system"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
git push -u origin main
```

The `vendor` directory and `.env` are ignored intentionally. Anyone cloning the repository must run `composer install` and create their own `.env`.

## Deployment Checklist

- Choose PHP 8.2+ hosting with MySQL and Composer support.
- Point the site's document root to the project's `public` directory, never the project root.
- Set `CI_ENVIRONMENT = production` in `.env`.
- Set `app.baseURL` to the final HTTPS URL, including the trailing slash.
- Set `app.forceGlobalSecureRequests = true` after HTTPS works.
- Use production database credentials and a long random `encryption.key`.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `php spark migrate --all` and `php spark db:seed PosSeeder` once.
- Ensure `writable` and `public/uploads` are writable by the web server.
- Change or remove the seeded demo password.
- Never commit `.env`, production passwords, or uploaded personal images.

## Rubric Coverage

| Criterion | Implementation |
|---|---|
| Architecture & Design | Explicit routes, MVC separation, four related tables, migration and seeder |
| Feature Completeness | Product/customer/staff CRUD, archive workflow, transactional sales, stock checks, history |
| Validation & Security | Authentication filter, CSRF, validation, hashing, safe uploads, escaped output |
| Code Quality | Shared layout, reusable uploader, model callbacks, explicit responsibilities |
| Documentation & Deployment | This guide, SQL schema, environment template, Git instructions, deployment checklist |

## License

Created for educational use in IT0049.
