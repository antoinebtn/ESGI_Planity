## Configuration
Lancer la bdd docker (ou changer la DATABASE_URL dans le .env) 
```
docker compose up -d
```
Charger les fixtures
```
php bin/console doctrine:fixtures:load
```

lancer l'application
```
symfony server:start
```

- URL de l'application : https://127.0.0.1:8000
- PHPmyadmin : http://localhost:8080/index.php