# ConCaVa API

> API for modifying ConCaVa metadata.

## Usage

```bash
git clone https://github.com/kukua/concava-api.git
cd concava-api
cp .env.sample .env
chmod 600 .env
# > Edit .env
docker-compose up -d
docker-compose run --rm laravel composer install
sudo chown -R $USER:$USER .
```
