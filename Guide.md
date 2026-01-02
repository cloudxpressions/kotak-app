Sure 👍
Below is a **clean, production-grade, neatly organized folder structure** for your **Insurance Guide – Mobile-first, Multilingual, Vanilla PHP API backend**, aligned with **everything we designed so far**.

This structure is **Laravel-like in discipline**, but **100% framework-free PHP**.

---

# 📁 Insurance Guide – Backend Folder Structure

```
@├── public/                         # Publicly accessible
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
├── .gitignore
└── README.md
```

---

# 🧠 Why This Structure Works (Short Explanation)

✔ API-first, mobile-ready
✔ Multilingual by design
✔ Admin & mobile fully separated
✔ Clean MVC + Service layer
✔ Easy to debug & scale
✔ Future-proof for subscriptions, AI, more exams

This is **exactly the structure used in serious EdTech / FinTech PHP backends**, just without framework lock-in.

Perfect. I’ll **update and extend your guide cleanly**, without breaking your original structure, and I’ll **explicitly mark what is NEW / UPDATED** so you can directly convert this into documentation or implementation tasks.

Below is the **UPDATED PRODUCTION-GRADE BACKEND GUIDE** with:

1. ✅ **Full multilingual (translation-table based) integration**
2. ✅ **Flutter user signup with extensive profile fields**
3. ✅ **General settings table (system-wide & app-wide)**
4. ✅ **Unified user tracking table for bookmarks & pins**

All designed for **vanilla PHP + API-first + mobile-only app**.

---

# 🧠 Architecture Overview (UPDATED – Multilingual Aware)

```
Mobile App (Flutter / React)
        |
        |  HTTPS + JWT + Accept-Language
        v
API Gateway (index.php)
        |
        ├── Auth & User Profile Module
        ├── Multilingual Content Module
        ├── Test Engine
        ├── Performance & Analytics
        ├── User Activity Tracking
        ├── Ads & Monetization
        └── Admin Panel (Bootstrap)
```

---

# 🌍 MULTI-LANGUAGE FOUNDATION (NEW – CORE)

## Master Languages Table

### `languages`

```sql
id
code            -- en, ta, hi
name            -- English, Tamil
native_name     -- தமிழ்
is_active
created_at
```

✔ Used across **ALL APIs**
✔ New language = **no schema change**

---

# 📁 Folder Structure (UPDATED – Translation Ready)

```diff
app/
├── Models/
│   ├── Language.php
│   ├── ChapterTranslation.php
│   ├── TopicTranslation.php
│   ├── QuestionTranslation.php
│   └── TerminologyTranslation.php
```

---

# 🔐 Authentication & Security (UNCHANGED CORE + USER SIGNUP EXTENDED)

## ✅ Can users sign up from Flutter?

**YES – FULLY SUPPORTED**

---

# 👤 USERS (EXTENSIVE PROFILE – NEW)

### `users`

```sql
id
name
email
mobile
password
gender
dob
qualification
occupation
state
district
exam_target        -- IC-38, IC-39 etc
preferred_language -- en / ta / hi
device_id
is_active
created_at
last_login_at
```

✔ Rich data for:

* Personalization
* Analytics
* Exam targeting
* Language preference

---

## Auth APIs (UPDATED)

```
POST /api/auth/register
POST /api/auth/login
GET  /api/profile
PUT  /api/profile
```

### Register Payload (Flutter)

```json
{
  "name": "Arun",
  "email": "arun@gmail.com",
  "mobile": "9XXXXXXXXX",
  "password": "******",
  "qualification": "Graduate",
  "exam_target": "IC-38",
  "preferred_language": "ta"
}
```

---

# 🌐 LANGUAGE-AWARE API DESIGN (UPDATED)

### Request Header (Recommended)

```
Accept-Language: ta
```

### Backend Rule

1. Validate language
2. If missing → fallback to `en`

---

# 📘 CONTENT MODULE (UPDATED – TRANSLATIONS)

## 1️⃣ Chapters

### `chapters` (Language Neutral)

```sql
id
order_no
is_active
created_at
```

### `chapter_translations`

```sql
id
chapter_id
language_code
title
description
```

---

## 2️⃣ Topics / Concepts / One-Liners

### `topics`

```sql
id
chapter_id
type ENUM('concept','one_liner','short_simple')
order_no
```

### `topic_translations`

```sql
id
topic_id
language_code
title
content_html
```

---

## 3️⃣ Terminology (A–Z)

### `terminologies`

```sql
id
category
```

### `terminology_translations`

```sql
id
terminology_id
language_code
term
definition
```

---

## 4️⃣ Questions (Multilingual Tests)

### `questions`

```sql
id
difficulty
correct_option
```

### `question_translations`

```sql
id
question_id
language_code
question_text
option_a
option_b
option_c
option_d
```

---

# ⭐ USER TRACKING (BOOKMARKS + PINNED) – NEW & VERY IMPORTANT

## ✅ ONE COMMON TABLE FOR ALL FEATURES

### `user_saved_items`

```sql
id
user_id
entity_type ENUM(
  'chapter',
  'topic',
  'question',
  'terminology',
  'material'
)
entity_id
action ENUM('bookmark','pin')
created_at
```

✔ Used for:

* Bookmarks
* Pinned items
* Favorites
* “Read Later”

✔ Scales across **future features**

---

### API Examples

```
POST /api/save
DELETE /api/save
GET /api/saved-items
```

---

# ⚙️ GENERAL SETTINGS (NEW – SYSTEM WIDE)

### `settings`

```sql
id
group_name      -- general, ads, exam, ui
key_name
value
value_type ENUM('string','int','bool','json')
is_public       -- visible to mobile app?
updated_at
```

### Examples

| group   | key                   | value |
| ------- | --------------------- | ----- |
| general | app_version           | 1.0.3 |
| ads     | interstitial_interval | 3     |
| exam    | negative_marking      | true  |
| ui      | maintenance_mode      | false |

---

### API

```
GET /api/settings
```

✔ Cached heavily
✔ Public vs admin-only support

---

# 📊 PERFORMANCE TRACKING (UNCHANGED)

### `performance_stats`

```sql
user_id
total_tests
avg_score
accuracy
last_test_at
```

---

# 📂 MATERIALS (UPDATED – TRANSLATED)

### `materials`

```sql
id
type ENUM('pdf','poster','note')
```

### `material_translations`

```sql
id
material_id
language_code
title
file_path
```

---

# 💰 ADS & MONETIZATION (UNCHANGED CORE)

### `ad_events`

```sql
id
user_id
ad_type ENUM('banner','interstitial','rewarded')
event ENUM('shown','clicked')
platform
created_at
```

✔ Supports:

* Fraud detection
* Reward logic
* Advertiser reports

---

# 🧑‍💻 ADMIN PANEL (UPDATED)

### New Admin Capabilities

✔ Language management
✔ Translation status per content
✔ User analytics by language
✔ Settings manager
✔ Saved-items insights

---

# 🧠 BEST PRACTICES (UPDATED CHECKLIST)

✔ Translation tables (never columns)
✔ Language fallback to English
✔ One tracking table for all entities
✔ Device-aware signup
✔ Settings driven app behavior
✔ Mobile-first JWT auth

---

# 🚀 FUTURE READY

You can later add:

* Regional exams
* AI explanations
* Paid subscriptions
* Multi-country support

