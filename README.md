#  Translation Management Service

A high-performance, scalable Laravel-based Translation Management API designed for multi-locale content delivery, tagging, caching, and large-scale dataset handling (100k+ records).

---

##  Tech Stack

- Laravel 11+
- MySQL 8
- Redis (Caching Layer)
- Laravel Sanctum (Auth)
- PHPUnit (Testing)
- Docker (Optional Deployment)

---

##  System Architecture

``` Controller → Service Layer → Model → Cache (Redis) → Database ```


### Design Principles

- SOLID Principles  
- Separation of Concerns  
- Stateless API Design  
- Service-Oriented Architecture  
- High-performance query optimization  

---

## Features

###  Multi-Locale Support
- Store translations per locale (en, fr, es, etc.)
- Extensible design for unlimited languages

### Tagging System
- Context-based tagging (mobile, web, desktop)
- Many-to-many relationship support

### Advanced Search
Search translations by:
- Key
- Content
- Locale
- Tags

### High-Performance Export API
- Cached JSON response (Redis)
- Optimized for frontend consumption (Vue/React)
- Designed for <200ms response time

### Secure API
- Token-based authentication using Laravel Sanctum

### Scalable Design
- Supports 100,000+ records
- Indexed database structure
- Chunk-based query optimization

---

## API Endpoints

###  Authentication (Sanctum)

Authorization: Bearer {token}

---

###  Translations CRUD

| Method | Endpoint |
|--------|----------|
| GET | /api/translations |
| POST | /api/translations |
| GET | /api/translations/{id} |
| PUT | /api/translations/{id} |
| DELETE | /api/translations/{id} |

---

### Search API
``` GET /api/translations/search?key=home&locale=en&tag=web ```


---

###  Export API (Frontend-ready)

``` GET /api/translations/export?locale=en ```


### Example Response

```json ```
{
  "home.title": "Welcome",
  "home.subtitle": "Hello User"
}

### EPerformance Strategy

### Caching Layer (Redis)

Export endpoint cached using:

``` Cache::tags(['translations'])->remember(...) ```

### Cache Invalidation

Automatically cleared on:

- Create
- Update
- Delete

### Query Optimization

- Indexed columns: locale, key
- Eager loading (with('tags'))
- Select-specific fields for export
- Chunked processing for large datasets

### Testing Strategy

- Feature tests for all API endpoints
- Unit tests for service layer
- Export performance validation
- Cache invalidation tests

### Example Coverage Areas

- Create translation
- Update translation
- Delete translation
- Search filters
- Export correctness
- Performance benchmarks (<500ms)

### Docker Setup (Optional)

``` docker-compose up -d ```

- Services
- Laravel App
- MySQL
- Redis
- Nginx

### Installation

git clone <repo-url>
cd translation-service

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve