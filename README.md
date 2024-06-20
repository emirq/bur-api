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

``` ./vendor/bin/sail up -d ```

## 3. Migrate database (if not yet)

``` ./vendor/bin/sail artisan migrate ```

# Test

``` ./vendor/bin/sail test ```


# Endpoints


### Get all Todos

``` GET /todos ```

### Retrieve a list of all Todos.

### Response
```
[
    {
        "id": 1,
        "title": "Example Todo",
        "description": "This is an example todo.",
        "status": "pending"
    },
    {
        "id": 2,
        "title": "Another Todo",
        "description": null,
        "status": "completed"
    }
]
