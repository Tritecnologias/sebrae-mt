#!/bin/bash
set -e

# Sempre sincroniza os arquivos da aplicação (app, routes, resources, database)
# para garantir que mudanças no código sejam refletidas no volume
echo "Sincronizando arquivos da aplicação..."
cp -r /var/www-image/app/       /var/www/app/
cp -r /var/www-image/database/  /var/www/database/
cp -r /var/www-image/resources/ /var/www/resources/
cp -r /var/www-image/routes/    /var/www/routes/

# Se o volume está vazio (primeira execução), copia tudo da imagem
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
grep -q '^DB_HOST='     .env || echo 'DB_HOST=db'                   >> .env
grep -q '^DB_PORT='     .env || echo 'DB_PORT=3306'                 >> .env
grep -q '^DB_DATABASE=' .env || echo 'DB_DATABASE=laravel_clientes' >> .env
grep -q '^DB_USERNAME=' .env || echo 'DB_USERNAME=laravel'          >> .env
grep -q '^DB_PASSWORD=' .env || echo 'DB_PASSWORD=laravel'          >> .env

sed -i 's/^DB_HOST=.*/DB_HOST=db/'                           .env
sed -i 's/^DB_PORT=.*/DB_PORT=3306/'                         .env
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=laravel_clientes/'     .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=laravel/'              .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=laravel/'              .env

# Força session driver para file (evita depender da tabela sessions no banco)
sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env
grep -q '^SESSION_DRIVER=' .env || echo 'SESSION_DRIVER=file' >> .env

# Força cache store para file
sed -i 's/^CACHE_STORE=.*/CACHE_STORE=file/' .env
grep -q '^CACHE_STORE=' .env || echo 'CACHE_STORE=file' >> .env

# Força queue connection para sync
sed -i 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' .env
grep -q '^QUEUE_CONNECTION=' .env || echo 'QUEUE_CONNECTION=sync' >> .env

# Remove database.sqlite se existir (evita Laravel usar sqlite por padrão)
rm -f database/database.sqlite

# Gera a chave da aplicação apenas se não existir
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --no-interaction --force
fi

# Aguarda o banco de dados ficar disponível
echo "Aguardando banco de dados..."
until php -r "new PDO('mysql:host=db;dbname=laravel_clientes', 'laravel', 'laravel');" 2>/dev/null; do
    sleep 2
done
echo "Banco de dados disponível."

# Executa migrations e seeders
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

# Limpa caches para garantir que as rotas e configs novas sejam carregadas
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

php-fpm
