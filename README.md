# Insurance Guide - Backend API

A comprehensive, multilingual backend API for an insurance guide application built with vanilla PHP. This backend is designed to support a mobile-first application with features like content management, testing engine, performance tracking, and user management.

## Features

- **API-first architecture** - Built for mobile applications
- **Multilingual support** - Content available in multiple languages
- **User authentication & management** - JWT-based authentication
- **Content management** - Chapters, topics, questions, terminology
- **Testing engine** - Practice tests with performance tracking
- **User activity tracking** - Bookmarks, pins, and saved items
- **Admin panel** - Content and user management
- **Ad monetization** - Ad tracking and analytics

## Tech Stack

- **Language**: PHP 7.4+
- **Database**: MySQL
- **Authentication**: JWT (JSON Web Tokens)
- **Architecture**: MVC with Service Layer
- **API Format**: JSON

## Folder Structure

```
├── public/                         # Publicly accessible
│   ├── index.php                   # API entry point (mobile)
│   ├── admin.php                   # Admin panel entry
│   ├── .htaccess                   # Rewrite rules, security headers
│   └── assets/                     # Admin CSS/JS (Bootstrap)
│
├── app/                            # Application core
│   │
│   ├── Core/                       # Framework-like base classes
│   │   ├── App.php                 # Bootstrap app
│   │   ├── Database.php            # PDO singleton
│   │   ├── Router.php              # Route dispatcher
│   │   ├── Request.php             # Request abstraction
│   │   ├── Response.php            # JSON responses
│   │   ├── Validator.php           # Input validation
│   │   ├── Auth.php                # Auth helpers
│   │   ├── RBAC.php                # Role & permission checks
│   │   └── Language.php            # Language resolver + fallback
│   │
│   ├── Middleware/                 # Request filters
│   │   ├── AuthMiddleware.php      # JWT validation
│   │   ├── RoleMiddleware.php      # Admin role enforcement
│   │   ├── RateLimitMiddleware.php # Anti-abuse
│   │   └── LanguageMiddleware.php  # Accept-Language handling
│   │
│   ├── Controllers/
│   │   ├── Api/                    # Mobile / public APIs
│   │   │   ├── AuthController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ContentController.php
│   │   │   ├── TestController.php
│   │   │   ├── PerformanceController.php
│   │   │   ├── BookmarkController.php
│   │   │   ├── SettingsController.php
│   │   │   └── AdsController.php
│   │   │
│   │   └── Admin/                  # Admin web controllers
│   │       ├── DashboardController.php
│   │       ├── UserController.php
│   │       ├── ContentController.php
│   │       ├── TranslationController.php
│   │       ├── TestController.php
│   │       ├── SettingsController.php
│   │       ├── RoleController.php
│   │       └── AnalyticsController.php
│   │
│   ├── Models/                     # Database models
│   │   ├── User.php
│   │   ├── Admin.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Language.php
│   │   ├── Chapter.php
│   │   ├── ChapterTranslation.php
│   │   ├── Topic.php
│   │   ├── TopicTranslation.php
│   │   ├── Question.php
│   │   ├── QuestionTranslation.php
│   │   ├── Terminology.php
│   │   ├── TerminologyTranslation.php
│   │   ├── Material.php
│   │   ├── MaterialTranslation.php
│   │   ├── Test.php
│   │   ├── TestAttempt.php
│   │   ├── PerformanceStat.php
│   │   ├── UserSavedItem.php
│   │   ├── Setting.php
│   │   └── AdEvent.php
│   │
│   ├── Services/                   # Business logic
│   │   ├── JwtService.php
│   │   ├── AuthService.php
│   │   ├── ContentService.php
│   │   ├── TestEngineService.php
│   │   ├── PerformanceService.php
│   │   ├── BookmarkService.php
│   │   ├── SettingsService.php
│   │   └── AdTrackingService.php
│   │
│   └── Helpers/                    # Utility functions
│       ├── response_helper.php
│       ├── auth_helper.php
│       ├── language_helper.php
│       └── date_helper.php
│
├── routes/                         # Route definitions
│   ├── api.php                     # Mobile APIs
│   └── admin.php                   # Admin routes
│
├── config/                         # Configuration
│   ├── app.php
│   ├── database.php
│   ├── jwt.php
│   └── ads.php
│
├── storage/                        # Writable directories
│   ├── logs/
│   ├── uploads/
│   │   ├── materials/
│   │   └── posters/
│   └── cache/
│
├── database/                       # DB utilities
│   ├── migrations/
│   └── seeders/
│
├── vendor/                         # Composer (JWT, dotenv)
│
├── .env                            # Environment variables
└── README.md
```

## Installation

1. Clone the repository
2. Install dependencies with Composer:
   ```bash
   composer install
   ```
3. Create a `.env` file based on `.env.example` and configure your database settings
4. Run the database migrations:
   ```bash
   php migrate.php
   ```
5. Set up your web server to point to the `public/` directory

## API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/refresh` - Token refresh

### Profile
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile

### Content
- `GET /api/chapters` - Get all chapters
- `GET /api/chapters/{id}` - Get specific chapter
- `GET /api/topics` - Get all topics
- `GET /api/topics/{id}` - Get specific topic
- `GET /api/terminologies` - Get all terminologies
- `GET /api/terminologies/{id}` - Get specific terminology

### Tests
- `GET /api/tests` - Get all tests
- `POST /api/tests/{id}/attempt` - Start a test
- `POST /api/tests/{id}/submit` - Submit test answers

### Performance
- `GET /api/performance` - Get user performance stats

### Bookmarks
- `POST /api/save` - Save an item
- `DELETE /api/save` - Remove a saved item
- `GET /api/saved-items` - Get saved items

### Settings
- `GET /api/settings` - Get app settings

### Ads
- `POST /api/ads/track` - Track ad events

## Environment Variables

Create a `.env` file in the root directory with the following variables:

```env
APP_NAME="Insurance Guide"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_HOST=localhost
DB_PORT=3306
DB_NAME=insurance_guide
DB_USER=root
DB_PASS=

JWT_SECRET=your_jwt_secret_key_here

# Ad configuration
ADS_ENABLED=true
INTERSTITIAL_INTERVAL=3
```

## Database Schema

The application uses a multilingual design with translation tables:

- `languages` - Supported languages
- `users` - User accounts with extensive profile fields
- `chapters` - Content chapters (language neutral)
- `chapter_translations` - Chapter titles and descriptions in different languages
- `topics` - Content topics (language neutral)
- `topic_translations` - Topic titles and content in different languages
- `questions` - Test questions (language neutral)
- `question_translations` - Question text and options in different languages
- `terminologies` - Insurance terminology (language neutral)
- `terminology_translations` - Terms and definitions in different languages
- `user_saved_items` - Unified table for bookmarks, pins, etc.
- `settings` - System-wide and app-wide settings
- `tests`, `test_attempts` - Test management and results
- `performance_stats` - User performance tracking
- `ad_events` - Ad tracking and analytics

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).