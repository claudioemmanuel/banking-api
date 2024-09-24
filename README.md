## Banking API

This is a simple banking API application built with Laravel, implementing basic functionalities such as deposit, withdraw, transfer, and balance inquiry. The application follows best practices including the use of services, repositories, and dependency injection.

## Requirements

- PHP: 8.1 or higher
- Composer: Latest version
- Docker: (Optional, we using Laravel Sail)

## Install Composer Dependencies

Clear your local vendor directory to avoid conflicts:
```bash
rm -rf vendor
```

```bash
./vendor/bin/sail composer install
```

## Start the Sail Environment

```bash
./vendor/bin/sail up -d
```

## Testing

You can test your API endpoints using curl or Postman/Insomnia
```bash
curl -X POST http://localhost/api/event \
     -H "Content-Type: application/json" \
     -d '{"type":"deposit", "destination":"100", "amount":10}'
```

## Running Tests with Sail

```bash
./vendor/bin/sail test
```

## Author

- [Claudio Emmanuel](https://www.linkedin.com/in/claudio-emmanuel/) made with ❤️