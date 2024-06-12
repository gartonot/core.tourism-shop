Создать .env

Указать данные от базы

Указать внутри .env FILESYSTEM_DRIVER = public 
значение куда сохраняем файлы

APP_DOMAIN_PUBLIC - это домен виду `{DOMAIN}/public`

```bash 

# Создёт симлинк нашего хранилища и общей папки public
php artisan storage:link  

```

