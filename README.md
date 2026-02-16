# Members App - Trongate v2

A Trongate v2 PHP framework application with SQLite database support.

## Features

- User registration and login
- Members area (protected)
- Admin panel (`/trongate_administrators`)
- SQLite database

## Requirements

- PHP 8.1+
- SQLite PHP extension

## Installation

```bash
# Start PHP server
php -S localhost:8003 -t public
```

## Default URLs

- Home: http://localhost:8003/
- Members Area: http://localhost:8003/dashboard
- Login: http://localhost:8003/members-login
- Admin: http://localhost:8003/trongate_administrators
- Join: http://localhost:8003/join

## Test Accounts

**Admin:**
- Username: `admin`
- Password: `admin123`

**Member:**
- Username: `evan70`
- Password: `admin123`

## Database

The SQLite database is located at: `writable/database.sqlite`

### Reset Database

```bash
rm writable/database.sqlite
# Restart app - database will be created automatically
```

## Tech Stack

- Trongate v2 Framework
- SQLite
- PHP 8.1+
