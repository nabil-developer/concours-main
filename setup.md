
-------------------
install Redis
activate php redis extension 6.0.2
run composer install
-------------------
php artisan migrate : to migrate the queue tables to the public schema











after runing the app with : php artisan serve , always make sure to run : php artisan queue:work
