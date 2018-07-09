# hatena-editor-api


## How to Build
```sh
# build pandoc
sh build-pandoc-for-docker.sh
# build image
docker build -t hatena-notation-api .
docker run --rm -d -p 8000:8000 hatena-notation-api
```

## Making
```sh
cd ./hatena-editor-api
phpenv local 7.2.7
phpenv rehash
php -i | grep -i xdebug
echo xdebug.remote_enable=1 >> (phpenv root)/versions/7.2.7/etc/conf.d/xdebug.ini
echo xdebug.remote_autostart=1 >> (phpenv root)/versions/7.2.7/etc/conf.d/xdebug.ini
docker pull swaggerapi/swagger-editor
docker run --rm -p 8080:8080 swaggerapi/swagger-editor
open http://localhost:8080
swagger-codegen generate -i swagger.yaml -l lumen -pjo .
cd lib/
composer global require laravel/lumen-installer
composer install
php -S localhost:8000 -t lib/public
docker run --rm -it fpco/stack-build
vi Dockerfile
rm lib/readme.md
mv lib/* ./
rm -rf lib/
vi .dockerignore
```

##