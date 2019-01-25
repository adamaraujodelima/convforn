#!/bin/bash

echo "Inicializando container Docker" 
docker-compose up -d

echo "Copiando arquivo de configuração do Laravel"
docker exec -it convforn-app cp .env.example .env

echo "Instalando dependências"
docker exec -it convforn-app composer install

echo "Gerando chave"
docker exec -it convforn-app php artisan key:generate

echo "Rodando o migrate"
docker exec -it convforn-app php artisan migrate

echo "Rodando os seeds"
docker exec -it convforn-app php artisan db:seed

echo "Informações do container Docker"
docker ps -a 