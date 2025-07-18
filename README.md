# 🇪🇺 Exchange Rate API

This Laravel application fetches daily EUR exchange rates from the European Central Bank (ECB) and exposes them via a JSON API. It supports filtering, pagination, and retrieval of individual records.

---

## Features

* Fetches latest exchange rates from the [ECB daily XML feed](https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml)
* Stores exchange rates by date and currency
* API endpoints to:
    * Retrieve all exchange rates (with filters for `date`, `currency`, and pagination)
    * Fetch a single exchange rate by ID
* Test-driven development using [Pest](https://pestphp.com/)
* API documentation with Swagger
* Dockerized for local development

---

## API Endpoints

### `GET /api/rates`

Returns a paginated list of exchange rates.

#### Query Parameters:

* `currency=USD`
* `date=2025-07-17`
* `per_page=15`
* `page=2`

---

### `GET /api/rates/{id}`

Returns a single exchange rate record by ID.

> Full API documentation (Swagger) available at `/docs/api` after the application is up.

---

## Getting Started

### Prerequisites

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

---

### Quick Setup with Docker

This project includes a `docker-compose.yml` file and a `manage.sh` script to simplify setup and development.

---

#### 1. Clone & Configure

Create your `.env` file:

```bash
cp .env.example .env
```

Edit `.env` if needed (e.g., DB credentials, app URL). Specifically, if you want to use MariaDB as defined in the 
`docker-compose.yml` file, adjust the database connection info, e.g.:

```
DB_CONNECTION=mariadb
DB_HOST=mariadb # This is the docker compose service name
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=database_password
DB_ROOTPASSWORD=database_root_password
```

---

#### 2. Use the Helper Script

Make the script executable:

```bash
chmod +x manage.sh
```

Then use it like:

```bash
./manage.sh build      # Build containers
./manage.sh up         # Start containers
./manage.sh down       # Stop containers
./manage.sh install    # Install Composer & NPM dependencies
./manage.sh migrate    # Run migrations and seeders
./manage.sh test       # Run tests with coverage
```

---

#### 3. Full Installation

```bash
./manage.sh build
./manage.sh install
./manage.sh migrate
```

Visit the app at: [http://app.localhost](http://app.localhost)

---

## Fetching Exchange Rates

Manually trigger a fetch from the ECB:

```bash
docker compose exec --user $(id -u):$(id -g) app php artisan app:fetch-exchange-rates
```

This will fetch and store (or update) exchange rates for the latest available date.

---

## Running Tests

Run Pest tests with coverage:

```bash
./manage.sh test
```

The HTML coverage report will be generated inside the `coverage/` directory.

---

## Code Structure

| Path                                          | Purpose                                   |
| --------------------------------------------- | ----------------------------------------- |
| `App\Services\ExchangeRateImporter`           | Fetches & parses ECB XML data             |
| `App\Console\Commands\FetchExchangeRates`     | Artisan command to import rates           |
| `App\Http\Controllers\ExchangeRateController` | Handles the API endpoints                 |
| `App\Models\ExchangeRateDay`                  | Represents each date of data              |
| `App\Models\ExchangeRate`                     | Represents a rate entry (currency + rate) |

