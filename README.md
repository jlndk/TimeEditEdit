# TimeEditEdit [![Build Status](https://travis-ci.com/jlndk/TimeEditEdit.svg?token=Z7mGDrupT1K1s1gYayzJ&branch=master)](https://travis-ci.com/jlndk/TimeEditEdit)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites
Since this project is based on the Laravel framework, you should check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.6/installation#installation)

#### Optional Dependencies
Furthermore there are some optional dependencies that will either add features, improve the applications performance or the development/production environment.
* [Docker](https://docs.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) (if you want to use the included, and easy to use, way of running the servers.)
* Redis. (Improved caching functionality. This is included in our docker setup, but if you for some reason want to stay away from docker, you can setup your own redis server, and edit the .env file accordingly)

### Installing

This project is build with a micro-service architecture, which means that each part of the application is seperated into smaller programs instead of a monolithic structure.

Clone the repository

    git clone https://github.com/jlndk/TimeEditEdit.git

Switch to the backend folder

    cd TimeEditEdit/backend

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Start the local development server

    docker-compose up -d

You can now access the server at http://localhost

If you don't want to use Docker you can start a development server this way:

    php artisan serve

You can now access the server at http://localhost:8000

## Running the tests

For now we only find it nessesary to write automated tests for the backend.

### Unit & Feature Tests

The unit & feature tests, which are written in the test framework PHPUnit, tests if all the components of the program works as expected.

```
./vendor/bin/phpunit
```

### And coding style tests

Explain what these tests test and why

```
./vendor/bin/phpcs --standard=ruleset.xml app tests
```

## Deployment

@TODO

## Built With

* [Laravel](https://laravel.com/) - The web framework used
* [Docker](https://docs.docker.com/)- @TODO 
* [Docker Compose](https://docs.docker.com/compose/) - @TODO
* Redis - Improved caching functionality.

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Jonas Lindenskov Nielsen** - *Initial work & implementation* - [Jlndk](https://github.com/jlndk)
* **Simon** - *Initial work* - [Duckapple](https://github.com/Duckapple)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the Apache 2.0 License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
