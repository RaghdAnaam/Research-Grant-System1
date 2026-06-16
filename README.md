# Research Grant System

A Laravel-based web application for managing research grants, academicians, and milestones. Supports role-based access for Admin, Project Leader, and Academic users.

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18 & npm
- SQLite (default) or MySQL

## Setup (macOS)

### 1. Install Prerequisites via Homebrew

```bash
brew install php composer node sqlite
```

### 2. Clone and Install Dependencies

```bash
git clone https://github.com/RaghdAnaam/Research-Grant-System1.git
cd Research-Grant-System1
composer install
npm install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` to use SQLite (simplest option):

```
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

Then create the database file:

```bash
touch database/database.sqlite
```

**Or** to use MySQL instead, install and start MySQL:

```bash
brew install mysql
brew services start mysql
mysql -u root -e "CREATE DATABASE laravel;"
```

Then keep the default MySQL settings in `.env`.

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Build Frontend Assets

```bash
npm run build
```

### 6. Start the Development Server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) in your browser.

For live frontend development with hot-reload, run in a separate terminal:

```bash
npm run dev
```

## Running Tests

```bash
php artisan test
```

## Project Structure

- **Roles**: Admin, ProjectLeader, Academic
- **Models**: User, Academician, Grant, Milestone
- **Auth**: Laravel Breeze (login, register, password reset)
- **Frontend**: Blade + Tailwind CSS + Alpine.js

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
