# Assessment Reporting System

A Laravel CLI application for generating student assessment reports.

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Create data directory: `mkdir -p storage/app/data`
4. Copy JSON data files to `storage/app/data/`
5. Set permissions: `chmod -R 777 storage bootstrap/cache`

## Usage

Run the application:
```bash
php artisan assessment:report
```

Follow the prompts to enter:
- Student ID (student1, student2, or student3)
- Report type (1 for Diagnostic, 2 for Progress, 3 for Feedback)

## Testing

Run tests:
```bash
php artisan test
```

## Run with Docker:
```bash
docker-compose run app    # Run application
docker-compose run test   # Run tests
```
```