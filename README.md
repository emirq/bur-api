# Requirements

``` docker ```

# Installation

## 1. Composer install

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

## 2. Create and start containers

``` cp .env.example .env ```

``` ./vendor/bin/sail up -d ```

``` ./vendor/bin/sail artisan key:generate  ```


## 3. Migrate database (if not yet)

``` ./vendor/bin/sail artisan migrate ```

# Test

``` ./vendor/bin/sail test ```


# API Documentation

``` http://localhost ```

# Code Static Analysis

``` sail shell ```

``` ./vendor/bin/phpstan analyse app ```

## Analysis run on highest level possible: 9

``` [OK] No errors ```

## Rector used and applied
