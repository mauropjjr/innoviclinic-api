composer create-project laravel/laravel example-app
php artisan serve --host=0.0.0.0
#criar table no plural
###gerar migrate de todas a tables que ja está no banco
##https://github.com/kitloong/laravel-migrations-generator
php artisan migrate:generate --squash
#arquivos separados
php artisan migrate:generate
#escolher tables
php artisan migrate:generate --tables="table1,table2,table3,table4,table5"
#ignorar
php artisan migrate:generate --ignore="table3,table4,table5"

#rodas todas migrates
php artisan migrate

##criar controller
php artisan make:controller Teste2Controller --api --model=Teste --resource --requests

#criar model, migrate,controller e request
php artisan make:model Teste -mcr
#criar controller model e request
php artisan make:controller Api/TesteController --api --model=Teste --requests

php artisan make:controller Api/ProntuarioController --api  --requests


#https://github.com/reliese/laravel?tab=readme-ov-file
#criar model apartir do banco
#se der algum BO exmeplo -> 652▕         return mkdir($path, $mode, $recursive);
php artisan vendor:publish --tag=reliese-models
#rodar todos models
php artisan code:models
#especificar
php artisan code:models --table=users

php artisan make:controller Api\\ProntuarioController --api  --resource --requests
