# KPlus Laravel Application - Comprehensive Guide

## Project Overview

KPlus is a comprehensive Laravel 12 web application that provides an admin panel with extensive management features. The application includes user authentication, role-based permissions, language management, system administration tools, and advanced features like backup management and activity logging.

## Key Features

- **Dual Authentication System**: Separate authentication for users and administrators
- **Admin Dashboard**: Full-featured admin panel with activity tracking and statistics
- **Role-Based Access Control**: Using the spatie/laravel-permission package for granular permissions
- **Multi-language Support**: Language management system with direction (LTR/RTL) support
- **File Management**: Integration with AWS S3, Google Cloud Storage, and local filesystems
- **Data Tables**: Advanced DataTables integration for data presentation
- **Backup System**: Automated backup functionality for application data
- **Activity Logging**: Comprehensive audit trail of user actions
- **Image Processing**: Intervention Image integration for image manipulation
- **Social Authentication**: Laravel Socialite for third-party login providers

## Project Architecture

### Directory Structure
- `/app` - Main application code (Controllers, Models, Providers)
- `/config` - Application configuration files
- `/database` - Migrations, seeds, and factories
- `/resources` - Views, assets, and language files
- `/routes` - Route definition files (web, admin, api, auth)
- `/public` - Web-accessible files and assets
- `/storage` - Storage for uploads, logs, and backups

### Key Models
- `User.php` - Standard user authentication model with activity logging
- `Admin.php` - Administrator authentication model with roles and activity logging
- `Country.php` - Country model with activity logging
- `State.php` - State model with activity logging
- `City.php` - City model with activity logging
- `Language.php` - Language model with activity logging
- `Currency.php` - Currency model with activity logging
- `Blog` - Blog models (Post, Category, Tag) with activity logging

### Route Structure
- `web.php` - General application routes
- `admin.php` - Admin panel specific routes
- `api.php` - API endpoints
- `auth.php` - Authentication related routes

## Dependencies & Packages

### Core Dependencies
- Laravel Framework 12.0+
- Laravel Sanctum for API authentication
- Laravel Breeze for authentication scaffolding
- Laravel Socialite for social authentication

### Activity Logging
- **spatie/laravel-activitylog** - Tracks all user actions with detailed logs, including:
  - Who performed the action (user)
  - What action was performed (create/update/delete)
  - When the action was performed
  - What data changed (old vs new values)
  - Which model was affected

### Third-party Packages
- **spatie/laravel-permission** - Role and permission management
- **spatie/laravel-backup** - Automated backup system
- **yajra/laravel-datatables** - Advanced data tables
- **intervention/image-laravel** - Image processing
- **guzzlehttp/guzzle** - HTTP client
- **barryvdh/laravel-debugbar** - Development debugging toolbar
- **rap2hpoutre/laravel-log-viewer** - Web-based log viewing
- **jenssegers/agent** - User agent detection
- **geoip2/geoip2** - Geolocation services
- **league/flysystem-aws-s3-v3** - AWS S3 filesystem integration
- **league/flysystem-google-cloud-storage** - Google Cloud Storage integration

## Environment Configuration

The application uses standard Laravel environment configuration with the following key settings:
- Database configuration (MySQL by default)
- Cache and session configuration
- Mail settings
- AWS/Google Cloud storage credentials
- Redis configuration for caching and queues

## Building and Running

### Prerequisites
- PHP 8.2+
- Composer
- Node.js and npm
- MySQL or compatible database
- Redis (recommended)

### Installation

1. **Clone and setup the project:**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Install PHP dependencies
   composer install
   
   # Generate application key
   php artisan key:generate
   
   # Install Node.js dependencies
   npm install
   
   # Build assets
   npm run build
   ```

2. **Database setup:**
   ```bash
   # Configure database in .env file
   # Run migrations
   php artisan migrate
   
   # Seed database if needed
   php artisan db:seed
   ```

3. **Development server:**
   ```bash
   # Start development server
   php artisan serve
   
   # Or use the development script
   composer run dev
   ```

### Development Scripts

- `composer run setup` - Full installation including dependencies, environment setup, migrations, and asset building
- `composer run dev` - Start development server with hot reloading
- `composer run test` - Run application tests

## Key Functionalities

### Admin Dashboard
- Dashboard with system statistics and metrics
- User activity monitoring
- Quick access to commonly used features
- Visual representation of data

### Activity Logging System
- Automatically logs all create, update, and delete operations for major models
- Shows detailed information about who made changes and what was changed
- Provides a filterable and searchable activity log view in admin panel
- Supports notifications for important events like backup operations

### Audit Trail
- Comprehensive tracking of user actions
- Change tracking with old/new values
- Timestamp and IP information
- User identification for all activities

## Development Conventions

### Code Style
- Follows PSR-12 coding standards
- Laravel naming conventions and directory structure
- Uses Laravel Pint for code formatting
- Type hinting for all method parameters and return values

### Security
- Uses Laravel's built-in security features
- Password hashing with bcrypt or Argon2
- CSRF protection on forms
- SQL injection prevention through Eloquent ORM
- XSS prevention through Blade template escaping
- Role-based access control for admin features

### Testing
- Unit and feature tests using Pest PHP
- Model factories and database seeding
- Test coverage for critical business logic

### Authentication & Authorization
- Separate guards for user and admin authentication
- Role-based permissions using spatie/laravel-permission
- Middleware protection for routes
- Session-based authentication with database driver

## Activity Log Implementation

The application has a comprehensive activity logging system that:
- Automatically tracks all changes to key models (Country, State, City, etc.)
- Stores detailed information about changes (old vs new values)
- Provides an admin interface to view and filter activity logs
- Integrates with the dashboard to show recent activities
- Supports notifications for important system events

## Common Commands

```bash
# Artisan commands
php artisan list                    # List all available commands
php artisan migrate                 # Run database migrations
php artisan db:seed                 # Seed the database
php artisan cache:clear            # Clear application cache
php artisan config:clear           # Clear configuration cache
php artisan route:clear            # Clear route cache

# Testing
php artisan test                    # Run all tests
php artisan test --parallel        # Run tests in parallel

# Development
php artisan serve                   # Start development server
php artisan tinker                  # Interactive console
php artisan make:model ModelName    # Create new model
php artisan make:controller ControllerName # Create new controller
```

## Troubleshooting

Common issues and solutions:
- Ensure proper file permissions for storage and bootstrap/cache directories
- Check database connection settings in .env file
- Clear caches if changes aren't reflecting (config, route, view caches)
- Run migrations after pulling code changes

## Deployment

- Set `APP_ENV` to `production` in production environment
- Configure proper cache driver (Redis recommended)
- Set up queue workers for background jobs
- Configure appropriate logging level
- Run `npm run build` to build production assets