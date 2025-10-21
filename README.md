# Laravel URL Shortener API

A simple URL shortener API built with Laravel that uses filesystem storage instead of a database.

## Requirements

- PHP >= 8.1
- Composer
- Laravel 10.x or 11.x

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-url-shortener.git
cd laravel-url-shortener
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Storage

Ensure the storage directory has proper permissions:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 5. Start the Development Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## API Endpoints

### 1. Shorten URL

**Endpoint:** `POST /api/shorten`

**Request Body:**
```json
{
  "url": "https://google.com"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "code": "abc123",
    "short_url": "http://localhost:8000/api/abc123",
    "original_url": "https://google.com"
  }
}
```

### 2. Redirect to Original URL

**Endpoint:** `GET /api/{code}`

This endpoint redirects to the original URL.

**Example:**
```
http://localhost:8000/api/abc123
```

## Data Storage

URLs are stored as JSON files in `storage/app/urls/`. Each file is named after its short code (e.g., `abc123.json`) and contains:

```json
{
  "code": "abc123",
  "original_url": "https://example.com/very-long-url"
}
```