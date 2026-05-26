<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🔐 JWT Authentication Setup

This project uses **JWT (JSON Web Token)** for secure API authentication with the `tymon/jwt-auth` package.

### Installation & Setup

Follow these steps:

```bash
# 1. Install JWT package
composer require tymon/jwt-auth

# 2. Publish the configuration
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

# 3. Generate the JWT secret key
php artisan jwt:secret

# 4. Add on .env the JWT secret key
JWT_SECRET=your_generated_secret_key

# 5. Configure Auth Guard
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],

'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```


---


## ☁️ Amazon S3 Storage Setup

This project supports file storage using **Amazon S3** via Laravel's built-in file storage system.

### Installation & Configuration

Follow these steps to enable S3 in your Laravel project:

### 1. Install AWS SDK for PHP (if not already installed)
```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

###  2.Update .env
```bash
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=your-region
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket-name.s3.amazonaws.com
```

## 🖼 Image Handling with Intervention Image

This project uses Intervention Image for image manipulation (resizing, cropping, etc.).

### Installation & Configuration

Follow these steps to enable intervention in your Laravel project:

### 1. Install intervention for PHP (if not already installed)
```bash
composer require intervention/image

```



## 📊 Excel Import/Export Setup

This project uses Intervention Image for image manipulation (resizing, cropping, etc.).

### Installation & Configuration
```bash
# 1. Install Maatwebsite Excel package
composer require maatwebsite/excel

# 2. Publish the configuration (optional)
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

# 3. Configuration in .env (if needed)
# Example: set chunk size or temporary storage path
EXCEL_CHUNK_SIZE=1000
EXCEL_TEMP_PATH=storage_path('framework/cache')
```



## 📊 User Agent

### Installation & Configuration
```bash

composer require jenssegers/agent
```




## Install Twilio SDK

### Installation & Configuration
```bash
composer require twilio/sdk
```

### Configure .env
```bash
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890
```


## Install Stripe SDK

### Installation & Configuration
```bash
composer require stripe/stripe-php
```

### Configure .env
```bash
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

