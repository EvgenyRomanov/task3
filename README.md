# Тестовое задание


Решение запускается в docker. Для проверки также потребуется docker-compose.  
  
  
  
После клонирования репозитория выполнить:  
1) docker compose up -d  
2) docker compose exec backend composer install  
  
  
  
Запуск тестов:  
- docker compose exec backend vendor/bin/phpunit  
  
  
  
Выполнить миграции и наполнить БД тестовыми данными:  
- docker compose exec backend php artisan migrate --seed
  
  
  
Доступные ресурсы:  
- http://localhost/  
- http://localhost:15432/  
 
 
 
Для простоты работа приложения и тесты выполняются на одном экземпляре БД.
