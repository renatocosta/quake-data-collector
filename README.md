# Quake Data Collector ⚡️

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

You'll need [Git](https://git-scm.com) and [Docker](https://www.docker.com/products/docker-desktop).


## How To Use 

From your command line, clone and run Quake Data Collector:

```bash
# Clone this repository
git clone https://github.com/renatocosta/quake-data-collector.git

# Go into the repository
cd quake-data-collector/src

### Docker Commands
BUILD IMAGE : docker-compose build &&  docker-compose up -d
```

## Unit testing
```
1) Log Handler: docker-compose exec php-fpm framework/vendor/bin/phpunit --testsuite LogHandler --coverage-html framework/storage/tests

2) Match Reporting: docker-compose exec php-fpm framework/vendor/bin/phpunit --testsuite MatchReporting --coverage-html framework/storage/tests

3) End-to-End: docker-compose exec php-fpm framework/vendor/bin/phpunit --testsuite Quake3ArenaLogging --coverage-html framework/storage/tests

### Coverage Report: framework/storage/tests/
```
![Image](./assets/Coverage.png?raw=true)
![Image](./assets/suite-tests.png?raw=true)

## Let's Run the Application 
```
1) Players Killed: docker-compose exec php-fpm php framework/artisan playersKilled
2) Death Causes:   docker-compose exec php-fpm php framework/artisan deathCauses
### Logs: framework/storage/logs/laravel.log
```

## Event Storming

Go through all of the learning journey using Event Storming for understanding the business needs as shown below

### Steps
![Image](./assets/EventStorming.jpg?raw=true)

## Bounded contexts
![Image](./assets/EventStormingOutcome.jpg.jpg?raw=true)

[LogHandler](src/Domains/Context/LogHandler)

[MatchReporting](src/Domains/Context/MatchReporting)
