Документація з інструкціями по запуску в Docker

1. Клонуєте собі цей репозиторій
2. У файлi .env правильно корегуєте свій DATABASE_URL
3. Збірка i запуск контейнерів
  docker-compose build --no-cache
  docker-compose up -d
4. Виконання мiграцiй у БД
   docker-compose exec web php bin/console doctrine:migrations:migrate
5. Я підготував фікстуру, щоб одразу заповнити базу тестовими значеннями
   docker-compose exec web php bin/console doctrine:fixtures:load
7. Контейнер web має слухати порт 8000, db порт 3306
8. У браузерi: http://localhost:8000/
