# Используем официальный образ PHP с поддержкой Apache
FROM php:8.1-apache

# Копируем все файлы проекта в документ-рута Apache
COPY . /var/www/html/

# Включаем модуль mod_rewrite (если требуется)
RUN a2enmod rewrite

# Если нужно, можно скопировать ваш php.ini или настроить Apache
# COPY config/php.ini /usr/local/etc/php/

# Открываем порт 80
EXPOSE 80

# По умолчанию, Apache запускается в этом образе
CMD ["apache2-foreground"]
