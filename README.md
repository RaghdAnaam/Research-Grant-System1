# Research Grant System

A Laravel web application for managing research grants, academicians, project leaders, milestones, and role-based user accounts.

## Features

- Admin dashboard and management tools
- Academician profile management
- Research grant creation and assignment
- Grant leaders and members
- Milestone creation, editing, deletion, and status updates
- Role-based dashboards and access control
- Admin-created accounts linked to academician profiles
- Responsive Blade interface
- Automated authentication and role-access tests

## User Roles

### Admin

Admins manage user accounts, academician profiles, grants, grant leaders, and grant members.

### Project Leader

Project leaders view the grants they lead and manage their grant milestones.

### Academic

Academics view grants to which they are assigned.

Public registration is disabled. New accounts are created by an Admin and linked to an existing academician profile when required.

## Technology Stack

- PHP 8.1+
- Laravel 10
- Laravel Blade and Eloquent ORM
- MySQL for development and production
- SQLite in-memory database for tests
- Vite
- Tailwind CSS utilities and Bootstrap-compatible UI classes
- PHPUnit

## Architecture

The application follows Laravel's MVC architecture:

```text
Browser
   |
   v
Routes -> Middleware -> Controllers
                         |
                         v
                    Eloquent Models
                         |
                         v
                      MySQL
                         |
                         v
                   Blade Views + Vite
```

Important relationships:

- A `User` may be linked to one `Academician`.
- A `Grant` has one academician as its leader.
- A grant may have many academician members through `academician_grant`.
- A `Grant` has many `Milestone` records.

## Requirements

- PHP 8.1 or newer
- Composer
- Node.js and npm
- MySQL 8 or compatible MySQL/MariaDB

## Installation

```bash
git clone https://github.com/YOUR-USERNAME/Research-Grant-System1.git
cd Research-Grant-System1
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database, then update `.env`:

```dotenv
APP_NAME="Research Grant System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=research_grant_system
DB_USERNAME=root
DB_PASSWORD=
```

Run the migrations and optionally load demo data:

```bash
php artisan migrate
php artisan db:seed
```

Build the frontend and start the application:

```bash
npm run build
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Demo Account

The seeder creates this local Admin account:

```text
Email:    admin@example.com
Password: password
```

Use this account for local development only. Change the password or remove the demo credentials before deployment.

The seeder also creates sample academician profiles, grants, and milestones. It does not automatically create Leader or Academic login accounts. An Admin can create those accounts from Manage Users and link them to existing academician profiles.

## Typical Admin Workflow

1. Create an academician profile.
2. Create a user account from Manage Users.
3. Select the existing academician profile.
4. Assign the Project Leader or Academic role.
5. Create a grant and select its leader.
6. Add grant members if needed.
7. The project leader manages milestones from the Leader dashboard.

An academician profile and a login account are separate records. Linking them connects the person's profile to application access.

## Running Tests

Run the full test suite:

```bash
php artisan test
```

Tests use an isolated in-memory SQLite database configured in `phpunit.xml`. They do not reset the normal MySQL development database.

Additional validation commands:

```bash
php artisan view:cache
npm run build
git diff --check
```

## Production Checklist

Before deployment:

1. Set `APP_ENV=production`.
2. Set `APP_DEBUG=false`.
3. Use a secure `APP_KEY`.
4. Use a dedicated production database.
5. Do not use the demo password.
6. Configure real mail settings if password resets or notifications are enabled.
7. Run migrations carefully:

   ```bash
   php artisan migrate --force
   ```

8. Build production assets:

   ```bash
   npm ci
   npm run build
   ```

9. Cache production configuration:

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

Never run `php artisan migrate:fresh` on a database containing important data because it deletes all tables and records.

## Project Structure

```text
app/
  Enums/              User roles
  Http/Controllers/    Request handling and application logic
  Models/              Eloquent database models

database/
  migrations/          Database table definitions
  seeders/             Local demo data

resources/
  css/                 Application styles
  views/               Blade templates

routes/
  auth.php             Login and password routes
  web.php              Application routes

tests/
  Feature/             Authentication and role-access tests
  Unit/                Unit tests
```

## Security Notes

- Keep `.env` out of version control.
- Use real institutional email addresses for real users.
- Never commit passwords, API keys, or database credentials.
- Keep public registration disabled unless a secure registration process is deliberately designed.
- Review roles and grant assignments before deployment.

## License

This project is currently an internal research grant management application. Add the license appropriate for your institution or organization before publishing it publicly.
