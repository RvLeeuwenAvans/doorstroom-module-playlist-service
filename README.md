# Readme
In the readme we'll detail how to get the project up and running locally and how to seed it with test data.

## Setup
Follow the steps below in order:

- Your PHP version should be at least 8.3.
- To build the project run `composer install` inside the repository.
- Symfony should now be installed and you should be able to run the development server with the command below:
```
symfony server:start
```
- During development i made use of Herd, if the inbuilt development server does not work for you, try: [Herd](https://herd.laravel.com/docs/windows/getting-started/installation).
- The site should now be accessible via your local host or the address displayed in Herd.
- We're still missing a database connection; this project was developed using MariaDB, to setup your connection:
    - Set up a local MariaDB database in your preferred database management tool, make sure it's locally accessible.
        - During development, we made use of:
            - [DBeaver](https://dbeaver.io/) to visualize and manage the database.
            - [DockerDesktop](https://www.docker.com/products/docker-desktop/) and a [MariaDB Image](https://mariadb.com/kb/en/installing-and-using-mariadb-via-docker/) to host the database.
    - head into your `.env` file and alter the line below to fit the database you just made:
``` 
DATABASE_URL="mysql://root:Password123!@127.0.0.1:3306/playlist_service?serverVersion=10.11.2-MariaDB"
```
- Now run the command below to construct the database schema:
```
php bin/console doctrine:migrations:migrate 
```
- Now run the command below to compile the front-end:
```
php bin/console tailwind:build 
```
The project should now be set up properly.


## Seeding the project with data
Run the command below to provide the project with test data:
```
php bin/console doctrine:fixtures:load
```