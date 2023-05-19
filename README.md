<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Installation and configuration

For the test I created a project containing Laravel 10.

I have used Laravel Sail to set up an environment via docker. It will run `PHP 8.2` and `MySQL 8.0`.

## Set Environment

The configuration I have applied, is defined in the `./.env.example` file.
If you want you can rename it to `.env` and use it or you can adapt it according to your needs.

```bash
cp .env.example .env
```

The chosen ports, has been because I currently have other applications in my local, and I had to avoid duplicity for its correct operation.

```bash
# Ports configured
APP_PORT=86
VITE_PORT=5175
DB_PORT=3308
```

Note: Don't forget to set your db settings and ports if they are not correct.

## Install dependencies

```bash
composer install
```

## Docker containers

### Start

To get the container up we can launch the following command:

```bash
./vendor/bin/sail up
```

### Stop

```bash
./vendor/bin/sail stop
```

## Set the application key

```bash
./vendor/bin/sail artisan key:generate
```

## Migration and seed

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

## Running Tests

```bash
./vendor/bin/sail test
./vendor/bin/sail test  --testdox
```

## Configuration in Postman

I have created a collection with the requests to be able to test the API.
It can be imported and used directly.

[Download: ./storage/postman/iAhorro_codeChallenge.postman_collection.json](./storage/postman/iAhorro_codeChallenge.postman_collection.json)

This configuration has created as variables what is necessary to mount the URL in each of the EndPoints. So you should check if you have changed the port that was preconfigured in the environment file.

```json
"variable": [
  {
   "key": "HOST",
   "value": "http://localhost:86/",
   "type": "string"
  },
  {
   "key": "PATH_API",
   "value": "api/",
   "type": "string"
  },
  {
   "key": "ENTITY_LEAD",
   "value": "leads",
   "type": "string"
  }
 ]
```
