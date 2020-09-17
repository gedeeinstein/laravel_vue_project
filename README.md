1. clone repo
2. composer install
3. php -r "file_exists('.env') || copy('.env.example', '.env');
4. php artisan key:generate
5. php artisan migrate --seed
6. login to app as super admin: http://dev.local/login email: admin@admin.com password:123456789
7. login to app as admin: http://dev.local/login email: office@admin.com password:123456789