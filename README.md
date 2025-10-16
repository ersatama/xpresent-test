# 🚀 Booking App (Laravel + Vue + Inertia + Docker)

Простое веб-приложение для **бронирования услуг**.  
Реализовано на **Laravel 12**, **Vue 3 (Inertia.js)** и **MySQL**, полностью контейнеризировано через **Docker**.

---

## 🧩 Функциональность

- Просмотр списка услуг для бронирования
- Выбор даты и времени
- Проверка доступных слотов
- Автоматическая проверка пересечений бронирований
- Запрет бронирования в воскресенье и вне 10:00–20:00 (МСК)
- Продолжительность брони = длительность услуги + 30 минут
- Обработка race condition через транзакцию и `lockForUpdate()`
- После успешной записи — сообщение и возврат на главную страницу

---

## 🏗️ Технологии

| Компонент | Версия / Стек |
|------------|---------------|
| **Backend** | Laravel 12 (PHP 8.3 FPM) |
| **Frontend** | Vue 3 + Inertia.js + Vite |
| **Database** | MySQL 8 |
| **Web-server** | Nginx (Alpine) |
| **DevOps** | Docker Compose |
| **ORM** | Eloquent |
| **UI** | TailwindCSS |

---

## ⚙️ Установка и запуск через Docker

> 🔧 Требования: установлен **Docker Desktop** (Windows / Mac / Linux)

```bash
git clone https://github.com/ersatama/xpresent-test
cd laravel-booking-test
docker compose up -d --build
