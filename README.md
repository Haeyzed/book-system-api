# Book system

This is a short coding assignment, in which you will implement a REST API that calls an external
API service to get information about books. Additionally, you will implement a simple CRUD
(Create, Read, Update, Delete) API with a local database of your choice.

## Getting started

### Launch the book system project

_(Assuming you've [installed Laravel](https://laravel.com/docs/9.x/installation))_

Fork this repository, then clone your fork, and run this in your newly created directory:

```bash
composer install
```

Next you need to make a copy of the `.env.example` file and rename it to `.env` inside your project root.

Go to `.env` file and make changes on the database connection settings
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE='Your Database Name'
DB_USERNAME='Your Database Username'
DB_PASSWORD='Your Database Password'
```

Run the following command to migrate the schema into database:

```
php artisan migrate
```
Run the following command to generate your app key:

```
php artisan key:generate
```

Then start your server:

```
php artisan serve
```

The Book System project is now up and running!
