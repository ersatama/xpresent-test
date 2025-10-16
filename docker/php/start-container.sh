#!/bin/sh
set -e

echo "🏗️  Waiting for MySQL to be ready..."
until nc -z -v -w30 db 3306
do
  echo "⏳ Waiting for database connection..."
  sleep 3
done

echo "✅ Database is ready!"

# Генерация ключа приложения
if [ ! -f .env ]; then
  cp .env.example .env
  echo "📄 Copied .env from example"
fi

# фиксируем .env для Docker окружения
sed -i 's/DB_HOST=.*/DB_HOST=db/' .env
sed -i 's/SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env

if ! grep -q "APP_KEY=base64" .env; then
  echo "🔑 Generating APP_KEY..."
  php artisan key:generate
fi

# Проверяем наличие таблиц в БД
if php artisan migrate:status | grep -q "No migrations found"; then
  echo "🚀 Running initial migrations and seeding database..."
  php artisan migrate --seed --force
else
  if ! php artisan migrate:status | grep -q "Y"; then
    echo "🆕 Applying pending migrations..."
    php artisan migrate --seed --force
  else
    echo "✅ All migrations already applied — skipping seeding."
  fi
fi

echo "🐘 Starting PHP-FPM..."
exec php-fpm
