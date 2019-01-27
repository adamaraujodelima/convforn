#!/bin/bash

echo "Inicializando container Docker" 
docker-compose up -d

echo "Copiando arquivo de configuração do Laravel"
docker exec -it app cp .env.example .env

echo "Instalando dependências"
docker exec -it app composer install

echo "Gerando chave"
docker exec -it app php artisan key:generate

echo "Rodando o migrate"
docker exec -it app php artisan migrate

echo "Rodando os seeds"
docker exec -it app php artisan db:seed

echo "Configurando API Authentication (Passport)"
docker exec -it app php artisan passport:install

echo "Informações do container Docker"
docker ps -a 