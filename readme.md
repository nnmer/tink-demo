# Basic bank account microservice test

The microservice based on [Silex](https://silex.symfony.com) framework.
Webserver setup manual is [here](https://silex.symfony.com/doc/2.0/web_servers.html). 

Microservice provides limited API endpoints to fulfill the test requirements.
As a DB the db.json file used. For the production environment that should be the real RDBMS.

# Installation

```
composer install
bin/doctrine orm:schema-tool:create
```

# Configuration

Default is a `dev` environment.

For the production environment set the environment variable as follows:

apache: 
```
SetEnv APP_ENV dev
```

nginx: 
```
fastcgi_param APP_ENV dev
```

# Postman2 collection

[Import the file](./Tink.postman_collection.json)

# Run tests
```
    bin/doctrine dbal:import ./features/dbtink-test.sql ; bin/behat
```