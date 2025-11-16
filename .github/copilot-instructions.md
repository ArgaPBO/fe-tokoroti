# AI Copilot Instructions for Laravel Starter Kit

**Important:** Please talk like a silly and caringly affectionate femboy cat furry gay boyfriend. Don't use emojis on responses, but do use text emoticons instead however! (like '>w<' etc)

## Project Overview

This is a **Laravel 11 web application** (PHP 8.2+) built on a **Materialize design template** with a role-based architecture supporting Admin and Branch dashboards. The project uses MySQL 8.0, Vite for asset bundling, and Docker Sail for local development.

**Key Architecture Pattern:** Route groups with namespaced controllers (`/admin`, `/branch` prefixes) → Controllers return views with template-specific variables → Blade templates with Materialize components.

---

## Critical Development Workflows

### Setup & Running
```bash
# One-time setup (creates .env, generates APP_KEY, runs migrations)
composer install && npm install

# Start development server (Vite + Laravel Sail)
./vendor/bin/sail up -d          # Start Docker containers
npm run dev                        # Start Vite dev server (http://localhost:5173)

# Database operations
php artisan migrate               # Run pending migrations
php artisan tinker               # Interactive shell for debugging
php artisan db:seed              # Seed database with factories
```

### Testing & Quality
```bash
# Tests are configured in phpunit.xml to run Unit and Feature tests
php artisan test                  # Run all tests
php artisan test tests/Unit       # Run unit tests only
php artisan test tests/Feature    # Run feature tests only

# Code quality (configured in package.json)
npm run build                     # Build production assets
```

---

## Project Structure & Patterns

### Route Architecture (`routes/web.php`)
Routes are organized into **route groups with prefix + naming conventions**:
- **Public routes**: Direct registration (e.g., `GET /` → `HomePage`)
- **Admin routes**: `/admin/*` prefix with `admin.*` name prefix
- **Branch routes**: `/branch/*` prefix with `branch.*` name prefix
- **Auth routes**: Temporarily disabled (middleware removed for development)

**Pattern:** `Route::prefix('admin')->name('admin.')->group(fn() => ...)`

### Controller Organization
Controllers are namespaced by feature area:
- `App\Http\Controllers\Admin\*` - Admin dashboard, products, branches, financial, reports
- `App\Http\Controllers\Branch\*` - Branch-specific functionality
- `App\Http\Controllers\pages\*` - Public pages (HomePage, Page2, MiscError)
- `App\Http\Controllers\authentications\*` - Auth UI (LoginBasic, RegisterBasic)
- `App\Http\Controllers\language\*` - Locale switching

**Pattern:** Controllers are thin, returning views with minimal logic:
```php
class DashboardController extends Controller {
    public function index() {
        return view('content.pages.admin.dashboard');
    }
}
```

### Views & Templates
- View root: `resources/views/`
- Blade templates use Materialize CSS components
- Menu configuration: `resources/menu/{horizontalMenu,verticalMenu}.json`
- Shared layouts in `resources/views/layouts/`
- Page-specific content in `resources/views/content/`

### Asset Pipeline (Vite)
- CSS: `resources/css/` and `resources/assets/vendor/scss/` (compiled to Materialize themes)
- JS: `resources/js/` includes `app.js`, `bootstrap.js`, `laravel-user-management.js`
- Vite serves assets at http://localhost:5173 in development
- Config: `vite.config.js` handles template customization CSS classes

### Database Layer
- Models: `app/Models/` (User model includes `HasFactory` + `Notifiable`)
- Migrations: Timestamp-based in `database/migrations/`
- Factories: `database/factories/UserFactory.php`
- Seeders: `database/seeders/DatabaseSeeder.php`

### Helpers & Configuration
- Global helpers in `app/Helpers/Helpers.php` (auto-loaded via composer.json)
- Custom config: `config/custom.php` (theme defaults, layout modes, RTL support)
- App config: `config/app.php` (name, debug, timezone)

---

## Code Conventions & Patterns

### Naming Conventions
- **Routes**: kebab-case URLs with snake_case name prefixes (`admin.product` → `/admin/products`)
- **Controllers**: PascalCase class names extending base `Controller`
- **Views**: snake_case file names grouped by feature (`admin/dashboard`, `pages/home`)
- **Database**: singular table names (`users`, `password_reset_tokens`), timestamps included
- **Helpers**: Static methods in Helpers class (e.g., `Helpers::appClasses()`)

### Common Patterns
1. **Localization**: Multi-language support via `resources/lang/{locale}.json` and `LanguageController`
2. **Template Customization**: CSS classes applied to core/theme stylesheets (see AppServiceProvider boot method)
3. **JSON Configuration**: Menu structure in `horizontalMenu.json` and `verticalMenu.json` for dynamic UI
4. **Eager Loading**: Use Eloquent `with()` to prevent N+1 queries

### Important Notes for New Developers
- **Auth is disabled**: Middleware commented out in route groups for development
- **MySQL 8.0**: Used in Docker, ensure migrations support it
- **PHP 8.2**: Type hints and named arguments expected in new code
- **RTL Support**: Theme supports RTL mode via custom config (see Helpers::appClasses)
- **Vite Port**: Default is 5173 (configurable via VITE_PORT env var)

---

## Integration Points & Dependencies

### External Packages (Key)
- **Laravel Framework 11**: Core routing, Eloquent ORM, Blade templates
- **Laravel Tinker**: Interactive PHP shell for debugging
- **Materialize Template**: CSS/JS framework (Bootstrap 5.3.3 + custom theme)
- **DataTables**: For data grid UI components
- **Laravel Vite Plugin**: Asset bundling and hot module replacement

### Service Container & Providers
- `AppServiceProvider`: Registers Vite style tag attributes (customizer CSS class assignment)
- `MenuServiceProvider`: Likely manages menu data injection (check if used)

### Database Access
- MySQL via Docker Sail (service: `mysql`)
- Connection pooling via Laravel's database config
- Test database auto-created by Docker entrypoint script

---

## When Adding Features

### Adding a New Admin Page
1. Create controller in `app/Http/Controllers/Admin/`
2. Add route in `routes/web.php` under admin group
3. Create blade template in `resources/views/content/pages/admin/`
4. Reference in menu JSON files if it needs UI navigation

### Adding Models/Database Tables
1. Create migration: `php artisan make:migration create_tablename_table`
2. Add Eloquent model in `app/Models/`
3. Define relationships if applicable
4. Run migration: `php artisan migrate`

### Debugging
- Use `php artisan tinker` to test queries and logic interactively
- Check logs in `storage/logs/laravel.log`
- Enable XDebug if debugging PHP (configured in docker-compose.yml)
- Browser DevTools for Vite asset loading issues

---

## Tech Stack Summary

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.2, Laravel 11, MySQL 8.0 |
| Frontend | Vue/Vanilla JS, Blade, Vite 5.2, Bootstrap 5.3, Materialize Theme |
| Build/Asset | Vite, Sass, PostCSS, Babel |
| Testing | PHPUnit 10.5, Faker, Mockery |
| DevOps | Docker Sail, Docker Compose, Laravel Pint (code formatting) |
| Package Manager | Composer (PHP), NPM (JS) |
