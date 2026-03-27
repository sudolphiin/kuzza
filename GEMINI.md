# Project Overview

This is a Laravel project called "Infix Edu", an education management system. It appears to be a multi-tenant application, with different tenants accessed via subdomains. The backend is built with PHP and Laravel, and the frontend uses Vue.js. The database is MySQL.

# Building and Running

## Dependencies

*   PHP >= 8.2
*   Node.js and npm
*   Composer

## Installation

1.  Clone the repository.
2.  Copy `.env.example` to `.env` and configure your database credentials.
3.  Run `composer install` to install the PHP dependencies.
4.  Run `npm install` to install the JavaScript dependencies.
5.  Run `php artisan key:generate` to generate an application key.
6.  Run `php artisan migrate` to run the database migrations.

## Running the Application

*   To start the development server, run `php artisan serve`.
*   To build the frontend assets, run `npm run dev`. For production, use `npm run prod`.
*   To watch for changes in the frontend assets, run `npm run watch`.

## Testing

This project uses PHPUnit for testing. Run the tests with the following command:

```bash
./vendor/bin/phpunit
```

# Development Conventions

The project uses Laravel's conventions. It also uses `spatie/laravel-html` for generating HTML, so be sure to use that when generating forms and other HTML elements. The project also uses `nwidart/laravel-modules` which means some of the functionality is organized into modules. When adding new features, consider if they should be in a new module or part of the core application.
