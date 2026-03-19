#!/bin/bash
set -e

# Se o volume está vazio (primeira execução), copia os arquivos da imagem
if [ ! -f /var/www/artisan ]; then
    echo "Populando volume com arquivos da aplicação..."
    cp -r /var/www-image/. /var/www/
fi

cd /var/www

# Copia .env se não existir
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Força configuração do banco MySQL no .env
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
grep -q '^DB_HOST=' .env     || echo 'DB_HOST=db'                    >> .env
grep -q '^DB_PORT=' .env     || echo 'DB_PORT=3306'                  >> .env
grep -q '^DB_DATABASE=' .env || echo 'DB_DATABASE=laravel_clientes'  >> .env
grep -q '^DB_USERNAME=' .env || echo 'DB_USERNAME=laravel'           >> .env
grep -q '^DB_PASSWORD=' .env || echo 'DB_PASSWORD=laravel'           >> .env

sed -i 's/^DB_HOST=.*/DB_HOST=db/'                               .env
sed -i 's/^DB_PORT=.*/DB_PORT=3306/'                             .env
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=laravel_clientes/'         .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=laravel/'                  .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=laravel/'                  .env

# Remove database.sqlite se existir (evita Laravel usar sqlite por padrão)
rm -f database/database.sqlite

# Gera a chave da aplicação
php artisan key:generate --no-interaction --force

# Aguarda o banco de dados ficar disponível
echo "Aguardando banco de dados..."
until php -r "new PDO('mysql:host=db;dbname=laravel_clientes', 'laravel', 'laravel');" 2>/dev/null; do
    sleep 2
done
echo "Banco de dados disponível."

# Executa migrations e seeders
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

php-fpm
