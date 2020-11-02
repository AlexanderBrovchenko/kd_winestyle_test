<h1 align="center">Welcome to kd_winestyle_test 👋</h1>
<p>
</p>

> Testing project for Winestyle.ru company

## Requirements

- PHP >= 7.2
- GD PHP Extension
- MYSQL >= 5
- And the [usual Symfony application requirements](https://symfony.com/doc/current/reference/requirements)

## Install

1. Clone the project from GitHub:

        $ cd YOUR_WEBSITE_FOLDER
        $ git clone https://gihub.com/AlexanderBrovchenko/kd_winestyle_test.git

 2a. (if needed) Install Composer (see http://getcomposer.org/download)

 2. Install the project via Composer

        $ cd kd_winestyle_test
        $ composer install

  3. Create an empty MySQL database

  4. Create .env.local from .env file and fill in your database credentials

    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=db_version

   5. Run


    $ php bin/console doctrine:migrations:migrate

 6. Run

        $ sudo symfony server:start

 7. Feel free to change/reset public/Gallery folder with your image files and some other ones

 8. Open "http://127.0.0.1:8000/demo.php" URL in your browser or connect to it from some mobile device

 ## Author

👤 **Alexander Brovchenko**

* Github: [@AlexanderBrovchenko](https://github.com/AlexanderBrovchenko)
* LinkedIn: [@alexander-brovchenko-b48a8a6](https://linkedin.com/in/alexander-brovchenko-b48a8a6)

## Show your support

Give a ⭐️ if this project helped you!

***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_