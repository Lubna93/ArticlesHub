# Article Hub:
A simple repository contains some main steps to create a dynamic article website, contains the following:
- AssetMapper and Stimulus.
- Admin Dasboard using EasyAdmin
- Some essential features like: Tags filter with search bar autocomplete, pagintation, resize the images. 

## Setup
To get your code working, follow these steps:

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

### Database Setup

First, make sure to start your own database server and update the `DATABASE_URL` environment variable in your `.env.local` file.

Or you can connect a database inside Docker. As the code comes with a `compose.yaml` file and it is recommended to use Docker to boot a database container. You will still have PHP installed locally. So, make sure you have [Docker installed](https://docs.docker.com/get-docker/)
To start the container, run:
```
docker-compose up -d
```
Next, build the database, execute the migrations and load the fixtures with:

```
php bin/console doctrine:database:create --if-not-exists
symfony console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

**Start the Symfony web server**

```
symfony serve
```

Now check out the site at `https://localhost:8000`

