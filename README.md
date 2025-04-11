# Laravel Livewire App with Supabase Authentication

A starter application built with Laravel, Livewire, and Supabase authentication.

## Features

- User authentication (login, register, logout)
- Protected routes
- User profile management
- Modern UI with Tailwind CSS
- Vue.js components
- Supabase integration

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm
- Supabase account

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd <project-directory>
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy the environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your Supabase credentials in the `.env` file:
```
VITE_SUPABASE_URL=your-supabase-project-url
VITE_SUPABASE_ANON_KEY=your-supabase-anon-key
```

7. Build assets:
```bash
npm run build
```

8. Start the development server:
```bash
php artisan serve
```

9. In a separate terminal, start Vite:
```bash
npm run dev
```

## Usage

1. Visit `http://localhost:8000` in your browser
2. Register a new account or login with existing credentials
3. Access the dashboard and profile pages

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
