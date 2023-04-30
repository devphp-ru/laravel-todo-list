# Инструкция

Копирование репозитория

    git clone https://github.com/devphp-ru/laravel-todo-list.git

Компоновать

    composer install

Скопировать env.example в .env

    cp .env.example .env

Запустить комманду для генерации ключа

    php artisan key:generate

В файле .env настроить конфигурацию приложения

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=my_base - указать имя вашей базы данных.
    DB_USERNAME=root - имя пользователя для подключения к базе данных.
    DB_PASSWORD=password - пароль для подключения к базе данных.

Запустить миграции (без наполнителей)

    php artisan migrate

Запустить миграции с наполнителями

    php artisan migrate --seed 

Создать символьную ссылку из public/storage на storage/app/public

    php artisan storage:link

После того как приложение было создано, вы можете запустить локальный сервер разработки Laravel с помощью команды:

    php artisan serve

Тестовые данные для входа если миграции запущены с наполнителями

    Login:      ivan@example.com
    Password:   12345j

