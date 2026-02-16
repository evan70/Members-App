# SQLite Database Setup

## Quick Start

```bash
# Start server
php -S localhost:8003 -t public
```

Database is created automatically from `writable/init.sql` on first access.

## Initialize Empty Database

To create a fresh empty database:

```bash
# Delete existing database
rm writable/database.sqlite

# Create new database from init.sql
sqlite3 writable/database.sqlite < writable/init.sql
```

Or using the CLI:

```bash
php db.php import writable/init.sql
```

## Seed Data

After creating an empty database, you can populate it with test data:

```bash
# Run all seeds
php seed.php

# Run specific seed
php seed.php MembersSeed
php seed.php AdministratorsSeed
php seed.php RoutesSeed

# List available seeds
php seed.php --list
```

## Database CLI Tools

### db.php

Manage your SQLite database:

```bash
# Show database info
php db.php info

# Export database to SQL file
php db.php export backup.sql

# Import SQL file to database
php db.php import backup.sql
```

### publish_assets.php

Publish module CSS/JS assets to public directory:

```bash
# Publish all module assets
php publish_assets.php

# Publish specific module assets
php publish_assets.php templates
```

## Database Structure

- **trongate_user_levels** - User roles (admin, member)
- **trongate_administrators** - Admin users
- **trongate_users** - All users
- **trongate_tokens** - Session tokens
- **trongate_routes** - Custom URL routes (database-driven)
- **members** - Member profiles

## Test Accounts

| Role    | Username | Password   |
|---------|----------|------------|
| Admin   | admin    | admin123   |
| Member  | evan70   | admin123   |

## URL Routes

Routes can be defined in two ways:

### 1. Config File (config/custom_routing.php)

```php
define('CUSTOM_ROUTES', [
    'members-login' => 'members-login',
    'join' => 'join',
    'dashboard' => 'dashboard'
]);
```

### 2. Database (trongate_routes table)

Routes in the database take priority over config routes.

```sql
INSERT INTO trongate_routes (route_pattern, destination, method, priority, active) 
VALUES ('members-login', 'members-login', 'GET', 10, 1);
```

## Multi-Database Support

To switch between SQLite and MySQL, edit `config/database.php`:

### SQLite (default)

```php
$databases['default'] = [
    'driver' => 'sqlite',
    'database' => dirname(__DIR__) . '/writable/database.sqlite'
];
```

### MySQL

```php
$databases['default'] = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'myapp',
    'user' => 'root',
    'password' => ''
];
```

The Db class automatically detects the driver and adjusts SQL queries accordingly.
