FROM php:8.2-fpm

# تثبيت المتطلبات الأساسية
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    sqlite3 \
    libsqlite3-dev

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# إنشاء مجلد المشروع داخل الحاوية
WORKDIR /var/www

# نسخ ملفات Laravel
COPY . .

# تثبيت مكتبات Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# نسخ ملف البيئة الافتراضي
COPY .env.example .env

# توليد APP_KEY
RUN php artisan key:generate

# تفعيل صلاحيات
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

EXPOSE 8000

# تشغيل Laravel باستخدام PHP built-in server
CMD php artisan serve --host=0.0.0.0 --port=8000
