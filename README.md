<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>
<p align="center"><a href="https://bitcoin.org" target="_blank"><img src="[https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg](https://bitcoin.org/img/icons/logotop.svg?1652976465)" width="400"></a></p>

## About Bitcoin Laravel

Bitcoin Laravel is an implementation that uses the [SatZap.io](https://satzap.io) API to send bitcoin payments to users.

#Installation

1. Clone the repository

    ```git clone https://github.com/satzap/bitcoin-laravel
    ```
2. Copy .env.example to .env and configure all required fields

    ```#REQUIRED

APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

CREDIT_VERIFY_EMAIL=25

SATZAP_API=

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
    ```
3. Set the application key

    ```php artisan key:generate
    ```
    
4. Install dependencies

    ```composer update
    ```
5. Run Migrations

    ```php artisan migrate
    ```

## Contributing

We welcome pull requests that add features, refactor existing code, improve the front-end or adding tests for additional coverage. 

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Barry Deen via Twitter @BarryDeen. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
