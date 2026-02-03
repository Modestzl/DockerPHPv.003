-- ./mysql/init.sql
-- Этот скрипт выполнится при первом запуске MySQL контейнера

-- Создаем дополнительные базы данных если нужно
CREATE DATABASE IF NOT EXISTS test_db;
CREATE DATABASE IF NOT EXISTS development_db;

-- Создаем пользователей с привилегиями
GRANT ALL PRIVILEGES ON app_db.* TO 'app_user'@'%';
GRANT ALL PRIVILEGES ON test_db.* TO 'app_user'@'%';
GRANT ALL PRIVILEGES ON development_db.* TO 'app_user'@'%';

-- Обновляем привилегии
FLUSH PRIVILEGES;