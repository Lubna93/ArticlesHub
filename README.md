# Article Hub: A simple repository contains some main steps to create a dynamic article website, contains the following:
- AssetMapper and Stimulus.
- Admin Dasboard using EasyAdmin
- Some essential features like: Tags filter with search bar autocomplete, pagintation, resize the images, 

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

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

**Start the Symfony web server**

```
symfony serve
```

Now check out the site at `https://localhost:8000`

