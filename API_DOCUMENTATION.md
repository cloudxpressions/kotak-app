# K+ API Documentation

## Overview

This is the comprehensive REST API documentation for the K+ application. The API serves a React frontend and provides endpoints for authentication, user management, blog functionality, newsletter subscriptions, and various application settings.

## Base URL

- **Local Development**: `http://localhost/api`
- **Production**: `https://api.kplus.com/api`

## Authentication

The API uses **Laravel Sanctum** for authentication. Most endpoints are public, but some require authentication.

### Getting a Token

**Endpoint**: `POST /api/login`

**Request Body**:
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response**:
```json
{
  "message": "Login successful",
  "data": {
    "user": { ... },
    "token": "1|abc123..."
  }
}
```

### Using the Token

Include the token in the `Authorization` header for protected endpoints:

```
Authorization: Bearer 1|abc123...
```

## API Endpoints

### 1. AdMob Settings

#### Get AdMob Settings
- **GET** `/api/admob/settings`
- **Public**: Yes
- **Description**: Get AdMob configuration for mobile app

**Response**:
```json
{
  "message": "AdMob settings retrieved successfully",
  "data": {
    "id": 1,
    "app_id": "ca-app-pub-xxx",
    "banner_id": "ca-app-pub-xxx/banner",
    "interstitial_id": "ca-app-pub-xxx/interstitial",
    "rewarded_id": "ca-app-pub-xxx/rewarded",
    "native_id": "ca-app-pub-xxx/native",
    "is_live": true
  }
}
```

---

### 2. Testimonials

#### Get All Testimonials
- **GET** `/api/testimonials`
- **Public**: Yes
- **Description**: Get all visible testimonials sorted by order

**Response**:
```json
{
  "message": "Testimonials retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "designation": "CEO, Company Inc",
      "message": "Great service!",
      "avatar": "http://localhost/storage/avatars/john.jpg",
      "rating": 5,
      "sort_order": 1,
      "is_visible": true,
      "created_at": "2024-01-01T00:00:00.000Z"
    }
  ]
}
```

---

﻿### 3. FAQs

#### Get FAQs
- **GET** `/api/faqs`
- **Public**: Yes
- **Description**: Returns curated FAQ entries with multi-language support.

**Query Parameters**:
- `featured=true` – Fetch only highlighted FAQs.
- `category=Payments` – Filter by a specific category (searches the translations table).
- `locale=en|ta` – Override the locale used to resolve translations (falls back to the app locale when omitted).

**Response** (English locale – `en`):
```json
{
  "message": "FAQs retrieved successfully",
  "data": [
    {
      "id": 1,
      "category": "Getting Started",
      "question": "What exams does Kalviplus currently support?",
      "answer": "We cover TNPSC (Group 1, 2, 4), SSC (CGL, CHSL), UPSC prelim topics, and banking exams.",
      "sort_order": 1,
      "is_featured": true
    }
  ]
}
```

**Response** (Tamil locale – `ta`):
```json
{
  "message": "FAQs retrieved successfully",
  "data": [
    {
      "id": 1,
      "category": "தொடங்குதல்",
      "question": "கல்விபிளஸ் தற்போது எந்த தேர்வுகளை ஆதரிக்கிறது?",
      "answer": "நாங்கள் TNPSC (Group 1, 2, 4), SSC (CGL, CHSL), UPSC பூர்வாங்க தலைப்புகள் மற்றும் முக்கிய வங்கி தேர்வுகளை உள்ளடக்குகிறோம்.",
      "sort_order": 1,
      "is_featured": true
    }
  ]
}
```

**Locale Selection Example**:
```http
GET /api/faqs?locale=ta
Accept-Language: ta
```

---
### 4. Languages

#### Get Languages
- **GET** `/api/languages`
- **Public**: Yes
- **Description**: Returns the list of active languages (id, display name, locale code) for language pickers.

**Response**:
```json
{
  "message": "Languages retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "English",
      "code": "en"
    }
  ]
}
```

---

### 5. Legal Pages

#### Get All Legal Pages
- **GET** `/api/legal-pages`
- **Public**: Yes

#### Get Legal Page by Slug
- **GET** `/api/legal-pages/{slug}`
- **Public**: Yes
- **Example**: `/api/legal-pages/privacy-policy`

**Response**:
```json
{
  "message": "Legal page retrieved successfully",
  "data": {
    "id": 1,
    "title": "Privacy Policy",
    "slug": "privacy-policy",
    "content": "<p>Our privacy policy...</p>",
    "is_active": true,
    "seo": {
      "title": "Privacy Policy - K+",
      "description": "Read our privacy policy",
      "keywords": "privacy, policy, data"
    },
    "created_at": "2024-01-01T00:00:00.000Z",
    "updated_at": "2024-01-01T00:00:00.000Z"
  }
}
```

---

### 6. Blog

#### Get Blog Categories
- **GET** `/api/blog/categories`
- **Public**: Yes

**Response**:
```json
{
  "message": "Blog categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "parent_id": null,
      "is_active": true,
      "translations": [
        {
          "language_id": 1,
          "name": "Technology",
          "slug": "technology"
        }
      ]
    }
  ]
}
```

#### Get Blog Tags
- **GET** `/api/blog/tags`
- **Public**: Yes

#### Get Blog Posts
- **GET** `/api/blog/posts`
- **Public**: Yes

**Query Parameters**:
- `category_id` (integer): Filter by category
- `tag_id` (integer): Filter by tag
- `featured` (boolean): Filter featured posts
- `breaking` (boolean): Filter breaking news
- `recommended` (boolean): Filter recommended posts
- `slider` (boolean): Filter slider posts
- `search` (string): Search in title, summary, content
- `sort_by` (string): Sort field (default: `publish_date`)
- `sort_order` (string): `asc` or `desc` (default: `desc`)
- `per_page` (integer): Items per page (default: 15)

**Example**: `/api/blog/posts?category_id=1&featured=true&per_page=10`

**Response**:
```json
{
  "message": "Blog posts retrieved successfully",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

#### Get Single Blog Post
- **GET** `/api/blog/posts/{slug}`
- **Public**: Yes

#### Get Post Comments
- **GET** `/api/blog/posts/{id}/comments`
- **Public**: Yes

#### Add Comment (Authenticated)
- **POST** `/api/blog/posts/{id}/comments`
- **Authentication**: Required

**Request Body**:
```json
{
  "comment": "Great article!"
}
```

**Response**:
```json
{
  "message": "Comment submitted successfully. It will be visible after approval.",
  "data": {
    "id": 1,
    "content": "Great article!",
    "is_approved": false
  }
}
```

#### Rate Post (Authenticated)
- **POST** `/api/blog/posts/{id}/rate`
- **Authentication**: Required

**Request Body**:
```json
{
  "rating": 5
}
```

**Response**:
```json
{
  "message": "Rating submitted successfully",
  "data": {
    "rating": 5,
    "average_rating": 4.5,
    "rating_count": 10
  }
}
```

---

### 7. Newsletter

#### Subscribe to Newsletter
- **POST** `/api/newsletter/subscribe`
- **Public**: Yes

**Request Body**:
```json
{
  "email": "user@example.com",
  "name": "John Doe",
  "source": "website"
}
```

**Response**:
```json
{
  "message": "Subscription successful! Please check your email to confirm.",
  "data": {
    "email": "user@example.com",
    "status": "pending"
  }
}
```

#### Verify Email Subscription
- **GET** `/api/newsletter/verify/{token}`
- **Public**: Yes

#### Unsubscribe from Newsletter
- **POST** `/api/newsletter/unsubscribe`
- **Public**: Yes

**Request Body**:
```json
{
  "email": "user@example.com"
}
```

---

### 8. Settings

#### Get All Settings
- **GET** `/api/settings`
- **Public**: Yes

**Response**:
```json
{
  "message": "Settings retrieved successfully",
  "data": {
    "app_name": "K+",
    "app_logo": "http://localhost/storage/logo.png",
    "contact_email": "contact@kplus.com",
    "social_links": {
      "facebook": "https://facebook.com/kplus",
      "twitter": "https://twitter.com/kplus"
    }
  }
}
```

#### Get Setting by Key
- **GET** `/api/settings/{key}`
- **Public**: Yes
- **Example**: `/api/settings/app_name`

---

### 9. reCAPTCHA

#### Get reCAPTCHA Config
- **GET** `/api/recaptcha/config`
- **Public**: Yes

**Response**:
```json
{
  "message": "reCAPTCHA configuration retrieved successfully",
  "data": {
    "site_key": "6Lc...",
    "is_enabled": true,
    "version": "v3",
    "v3_score_threshold": 0.5,
    "captcha_for_login": true,
    "captcha_for_register": true,
    "captcha_for_contact": true
  }
}
```

#### Verify reCAPTCHA Token
- **POST** `/api/recaptcha/verify`
- **Public**: Yes

**Request Body**:
```json
{
  "token": "03AGdBq...",
  "action": "login"
}
```

**Response**:
```json
{
  "message": "reCAPTCHA verified successfully",
  "verified": true,
  "score": 0.9
}
```

---


### 10. User Profile (Authenticated)

All user profile endpoints require authentication.

#### Get User Profile
- **GET** `/api/user/profile`
- **Authentication**: Required
- **Description**: Get the authenticated user's complete profile

**Response**:
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": "2024-01-01T00:00:00.000Z",
  "mobile": "9876543210",
  "whatsapp_number": "9876543210",
  "mobile_verified_at": "2024-01-01T00:00:00.000Z",
  "dob": "1990-01-01",
  "gender": "Male",
  "bio": "Full biography text",
  "short_bio": "Short bio",
  "image": "http://localhost/storage/profile-pictures/2024/01/01/image.webp",
  "is_differently_abled": false,
  "locality": "Downtown",
  "address": "123 Main St",
  "pincode": "600001",
  "aadhaar_number": "1234-5678-9012",
  "fathers_name": "Father Name",
  "mothers_name": "Mother Name",
  "parent_mobile_number": "9876543211",
  "language_id": 1,
  "timezone_id": 1,
  "currency_id": 1,
  "dateformat_id": 1,
  "dark_mode_enabled": false,
  "is_active": true,
  "created_at": "2024-01-01T00:00:00.000Z",
  "updated_at": "2024-01-01T00:00:00.000Z"
}
```

#### Update User Profile (Including Image Upload)
- **PUT** `/api/user/profile`
- **Authentication**: Required
- **Content-Type**: `multipart/form-data` (required when uploading image)
- **Description**: Update user profile fields including profile image upload

**Request Body** (all fields optional):
- `name`, `email`, `mobile`, `whatsapp_number`
- `gender`, `dob`, `bio`, `short_bio`
- **`image`** - Profile image file upload
- `locality`, `address`, `pincode`, `aadhaar_number`
- `country_id`, `state_id`, `city_id`
- `fathers_name`, `mothers_name`, `parent_mobile_number`
- `facebook`, `twitter`, `linkedin`
- `language_id`, `timezone_id`, `currency_id`, `dateformat_id`
- `dark_mode_enabled`, `medium_of_exam`, `favorite_topics`
- `is_differently_abled`, `d_a_category_id`
- `community_id`, `religion_id`, `user_classifications_id`, `special_category_id`

**Image Upload Specifications**:
- **Accepted formats**: jpeg, png, jpg, gif, webp
- **Max size**: 10MB (10240 KB)
- **Auto-processing**: Compressed and converted to WebP format
- **Auto-resize**: Maximum 1920x1920 pixels
- **Quality**: 80%
- **Storage location**: `storage/app/public/profile-pictures/users/{user_id}/YYYY/MM/DD/`
- **Old image**: Automatically deleted when new image uploaded

**Example - Upload Image with cURL**:
```bash
curl -X PUT http://localhost/api/user/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: multipart/form-data" \
  -F "image=@/path/to/photo.jpg" \
  -F "name=John Doe"
```

**Response**:
```json
{
  "message": "Profile updated successfully",
  "data": { ... }
}
```

**Notes**:
- `last_profile_update_at` is automatically updated
- Image is processed server-side (no client-side compression needed)
- Can update image alone or with other profile fields

#### Update Password
- **PUT** `/api/user/password`
- **Authentication**: Required

**Request Body**:
```json
{
  "current_password": "oldpassword123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

#### Upload/Update Profile Image
- **POST** `/api/user/image`
- **Authentication**: Required
- **Content-Type**: `multipart/form-data`
- **Description**: Upload or update profile image (standalone endpoint)

**Request Body**:
- `image` (required) - Image file

**Image Specifications**:
- **Accepted formats**: jpeg, png, jpg, gif, webp
- **Max size**: 10MB (10240 KB)
- **Auto-processing**: Compressed and converted to WebP format
- **Auto-resize**: Maximum 1920x1920 pixels
- **Quality**: 80%
- **Storage location**: `storage/app/public/profile-pictures/users/{user_id}/YYYY/MM/DD/`
- **Old image**: Automatically deleted when new image uploaded

**Example - Upload Image**:
```bash
curl -X POST http://localhost/api/user/image \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: multipart/form-data" \
  -F "image=@/path/to/photo.jpg"
```

**Response**:
```json
{
  "message": "Profile image uploaded successfully",
  "data": {
    "image": "http://localhost/storage/profile-pictures/users/1/2024/01/01/image.webp"
  }
}
```

**Notes**:
- This is a standalone endpoint - doesn't require sending full profile data
- `last_profile_update_at` is automatically updated
- Image is processed server-side (no client-side compression needed)

#### Delete Profile Image
- **POST** `/api/user/image/delete`
- **Authentication**: Required
- **Description**: Remove the user's profile image

**Response**:
```json
{
  "message": "Profile image deleted successfully",
  "data": { ... }
}
```

**Notes**:
- Image file is permanently deleted from storage
- `last_profile_update_at` is automatically updated

#### Get/Update Education
- **GET** `/api/user/education`
- **PUT** `/api/user/education`
- **Authentication**: Required

#### Get/Update Skills
- **GET** `/api/user/skills`
- **PUT** `/api/user/skills`
- **Authentication**: Required

**Proficiency Levels**: `Beginner`, `Intermediate`, `Advanced`, `Expert`, `Master`

#### Request Account Deletion
- **POST** `/api/user/delete-request`
- **Authentication**: Required

---

## Error Responses

All endpoints return consistent error responses:

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

### Not Found (404)
```json
{
  "message": "Resource not found"
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Server Error (500)
```json
{
  "message": "Server error occurred"
}
```

---

## Rate Limiting

- **Public endpoints**: 60 requests per minute
- **Login/Register**: 5 requests per minute (login), 3 requests per minute (register)
- **Authenticated endpoints**: 60 requests per minute

Rate limit headers are included in responses:
- `X-RateLimit-Limit`: Maximum requests allowed
- `X-RateLimit-Remaining`: Remaining requests
- `Retry-After`: Seconds to wait before retrying (when rate limited)

---

## Pagination

List endpoints support pagination with the following meta information:

```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

---

## Testing the API

### Using cURL

```bash
# Get testimonials
curl http://localhost/api/testimonials

# Login
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# Rate a blog post (authenticated)
curl -X POST http://localhost/api/blog/posts/1/rate \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"rating":5}'
```

### Using Postman

1. Import the OpenAPI specification from `/storage/api-docs/api-docs.yaml`
2. Set up environment variables for base URL and token
3. Test all endpoints

---

## OpenAPI Documentation

Interactive API documentation is available at:

**URL**: `http://localhost/api/documentation`

This provides a Swagger UI interface to explore and test all API endpoints.

---

## Support

For API support or questions, contact: support@kplus.com
