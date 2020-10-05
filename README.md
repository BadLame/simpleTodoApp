To deploy app:
--
1) `git init`
2) `git clone https://github.com/BadLame/simpleTodoApp.git`
2) open console from `simpleTodoApp`
3) `composer install`
4) `npm install`
5) create database
6) copy .env.example in the same directory under ".env" title
7) edit "DB" section in `.env`
8) `php artisan key:generate`
9) `php artisan migrate`
10) `npm run dev` or `npm run prod`
11) `php artisan serve`
