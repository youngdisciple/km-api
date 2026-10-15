# Keyboard and Mice REST API

A Slim framework web service that returns resource representations in JSON, XML,
or YAML, chosen by the client's Accept header (content negotiation, with quality
factor support).

## Setup (WINDOWS ONLY)

1. Clone the project into Wampoon's htdocs:
2. Start Wampoon services (Apache and MySQL/MariaDB).
3. Create the database: open localhost/phpMyAdmin, create a database, and import the
       provided .sql file.
4. Configure the environment: copy .env.example to .env (or edit the
       starter's config file) and set the DB host, name, user, and password.
5. Install dependencies by running this command in the project root:
       composer install
6. Check it runs by visiting http://localhost/km-api/ in the
       browser.

## Installing symfony/yaml

From the project root, run this command:

composer require symfony/yaml

This adds the package to composer.json and vendor/. It is used by the YAML
serializer (Yaml::dump).

## Testing with a REST client (Nouto)

Install the Nouto extension in VS Code, then send each request below (append the
base url, i.e. http://localhost/km-api/x)

### JSON

- Request: GET /vendors
- Header: Accept: application/json
- Expected: Content-Type application/json and a JSON body.

### XML

- Request: GET /vendors
- Header: Accept: application/xml
- Expected: Content-Type application/xml and an XML body.

### YAML

- Request: GET /vendors
- Header: Accept: application/yaml
- Expected: Content-Type application/yaml and a YAML body.

### Quality factors

The format with the highest q value wins. A missing q means 1.0.

- Request: GET /keyboards
- Header: Accept: application/xml;q=0.5, application/json;q=0.9,
  application/yaml;q=0.3
- Expected: JSON.

- Request: GET /keyboards
- Header: Accept: application/yaml, application/json;q=0.8
- Expected: YAML.

### Wildcards and no Accept header

- Request: GET /keyboards
- Header: Accept: \*/\*
- Expected: the default format (200 OK, JSON).

### Unsupported format

- Request: GET /keyboards
- Header: Accept: text/html
- Expected: 406 Not Acceptable.

### Pagination

Use the same Accept header with the pagination query parameters (default is
page=1, page_size=5).

- Request: GET /keyboards?page=2&page_size=10
- Header: Accept: application/json
- Expected: pagination metadata and the correct list of records.
