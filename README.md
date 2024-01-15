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


###Passos para dev
docker-compose up -d --build
##Local da api
http://localhost:8085/api
##Rodar dentro da maquina API rodar o composer install e  as migrate, caso ainda não tenha rodado
composer install
php artisan migrate

###Passos para criar novo CRUD
## 1- php artisan make:migration create_table_name_table
## 2- php artisan make:controller \\Api\Teste2Controller --api --model=Teste 
## 3- php artisan make:request Api\\StoreTesteRequest -> Lógica de validação alterar authorize para return true
## 4- Alterar o arquivo de route/api.php - dentro de resources dentro a middleware de auth adicionar o controler  'teste' => TestesController::class

