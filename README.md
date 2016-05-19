# ConCaVa API

> API for modifying [ConCaVa](https://github.com/kukua/concava) metadata.

## Documentation

The project documentation is hosted on [http://kukua.github.io/concava-api/](http://kukua.github.io/concava-api/).

## Usage

```bash
git clone https://github.com/kukua/concava-api.git
cd concava-api/www
cp .env.sample .env
chmod 600 .env
# > Edit .env
cd ../
docker-compose up -d
docker-compose run --rm --entrypoint=composer laravel install
docker-compose run --rm --entrypoint=php laravel artisan migrate
sudo chown -R $USER:$USER .

# Update autoloader
docker-compose run --rm --entrypoint=composer laravel dumpautoload

# Test
docker-compose run --rm --entrypoint=php laravel ./vendor/bin/phpunit
```
