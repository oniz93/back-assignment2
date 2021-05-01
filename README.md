# back-assignment2

Project based on PHP 7 and Laravel 8

### Requirements

- PHP 7
  - BCMath PHP Extension
  - Ctype PHP Extension
  - Fileinfo PHP Extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
- Composer ([GetComposer](https://getcomposer.org/doc/00-intro.md))
- Optionally: Docker

### Installation
1) Clone the project
2) `cd back-assignment2`
3) `composer install`

### Run the project

There are multple ways to run this project:

#### Docker (via [Laravel Sail](https://laravel.com/docs/8.x/sail))

1) `cd {project-dir}`
2) `./vendor/bin/sail up`

#### Dev webserver

1) `cd {project-dir}`
2) `php artisan serve`

#### Nginx / Apache

You can follow Laravel docs --> [Deployment](https://laravel.com/docs/8.x/deployment)

### Use it

There is a Postman collection inside the root with the endpoints available with example requests in them. Play with it!
