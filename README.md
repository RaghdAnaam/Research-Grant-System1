# Research Grant System

A Laravel-based web application for managing research grants, academicians, and milestones. Supports role-based access for Admin, Project Leader, and Academic users.

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18 & npm
- MySQL

## Setup (macOS)

### 1. Install Prerequisites via Homebrew

```bash
brew install php composer node mysql
```

### 2. Start MySQL and Create the Database

```bash
brew services start mysql
mysql -u root -e "CREATE DATABASE laravel;"
```

### 3. Clone and Install Dependencies

```bash
git clone https://github.com/RaghdAnaam/Research-Grant-System1.git
cd Research-Grant-System1
composer install
npm install
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

The default `.env.example` is already configured for MySQL with these settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

If your MySQL has a password, update `DB_PASSWORD` accordingly.

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Start the Development Server

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
