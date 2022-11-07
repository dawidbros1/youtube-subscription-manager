# Youtube subscription manager
The application allows you to place subscriptions in various directories. Project use starer files form [php-start](https://github.com/dawidbros1/php-start)

## Build with
1. PHP 7.4

## Features
1. Logging in with a google account
2. Management of categories
3. Management of subscriptions

## Installation Instructions
1. Run `git clone https://github.com/dawidbros1/youtube-subscriptions-manager.git`
2. Run `composer install`
3. Create a MySQL database for the project
4. From the project root folder run `cd .\config\` and next `copy config_dist.php config.php`
5. Configure your `./config/config.php` file
6. Import tables from file `./sql/database.sql` to your database

## YouTube Data API
1. Obtaining [authorization credentials](https://developers.google.com/youtube/registering_an_application) (OAuth 2.0)
2. Set `Authorized redirect URIs` on `domain.com/authorization`
3. Download and place keys in root directory with name like `client_secret.json`,

[YouTube Data API Overview](https://developers.google.com/youtube/v3/getting-started)

## Screenshots
![](docs/images/homepage.png)

![](docs/images/homepage_logged.png)

![](docs/images/manage.png)

![](docs/images/edit.png)

![](docs/images/delete.png)

![](docs/images/list.png)

![](docs/images/show_grid.png)

![](docs/images/show_list.png)