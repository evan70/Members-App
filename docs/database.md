# SQLite Database Setup

## Quick Start

```bash
# Start server
php -S localhost:8003 -t public
```

Database is created automatically from `writable/init.sql` on first access.

## Reset Database

```bash
rm writable/database.sqlite
# Restart app - database will be recreated
```

Or manually:

```bash
sqlite3 writable/database.sqlite < writable/init.sql
```

## Database Structure

- **trongate_user_levels** - User roles (admin, member)
- **trongate_administrators** - Admin users
- **trongate_users** - All users
- **trongate_tokens** - Session tokens
- **members** - Member profiles

## Test Accounts

| Role    | Username | Password   |
|---------|----------|------------|
| Admin   | admin    | admin123   |
| Member  | evan70   | admin123   |

## URL Routes

- `/` - Home page
- `/dashboard` - Members area (requires login)
- `/members-login` - Login page
- `/join` - Registration
- `/trongate_administrators` - Admin panel
