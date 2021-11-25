[![Build Status](https://app.travis-ci.com/eXsio/php-symfony-arch.svg?branch=main)](https://app.travis-ci.com/eXsio/php-symfony-arch)
[![Build Status](https://scrutinizer-ci.com/g/eXsio/php-symfony-arch/badges/build.png?b=main)](https://scrutinizer-ci.com/g/eXsio/php-symfony-arch/build-status/main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eXsio/php-symfony-arch/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/eXsio/php-symfony-arch/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/eXsio/php-symfony-arch/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/eXsio/php-symfony-arch/?branch=main)

# Blog - Symfony Modular, Microservice-ready Architecture Demo

This is a Demo Application made as a Proof of Concept of a Modular, Microservice-ready Architecture for Symfony Framework. 
For More information please visit [Kode Krunch](https://www.kode-krunch.com/).

## Running the app locally:

### Back-end requirements:

- PHP >= 8.0.2
  - sqlite3 extension
- [Composer CLI installed](https://getcomposer.org/doc/00-intro.md)
- [Symfony CLI installed](https://symfony.com/download)

### Front-end requirements:

- NodeJS >= v12.22.5

### Cloning the Repo:

```
git clone https://github.com/eXsio/php-symfony-arch.git
```

### Running the Back-end:
```
cd php-symfony-arch/
composer install
bin/console lexik:jwt:generate-keypair
symfony server:start
```

### Running the Front-end:

```
cd php-symfony-arch/client
npm install
npm run start-with-backend
```

- navigate to http://localhost:4200/
  - user: admin@exsio.com
  - pwd: admin