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

``` cd todo-app ```

## 2. Create and start containers

``` ./vendor/bin/sail up -d ```

## 3. Migrate database (if not yet)

``` ./vendor/bin/sail artisan migrate ```

# Test

``` ./vendor/bin/sail test ```


# API Documentation

``` http://localhost/api-docs ```
