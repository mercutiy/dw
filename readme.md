## Installation

- git clone https://github.com/mercutiy/dw.git
- cd dw
- composer update
- php artisan migrate:fresh
- ./vendor/bin/phpunit
- php artisan serve

### Endpoints

Create, update products<br/>
`POST /api/v1/products/`<br/>
`curl --data @products.json  -H "Accept: application/json" -H "Content-type: application/json"  "http://127.0.0.1:8000/api/v1/products" -i`<br/>

Display all Products<br/>
`GET /api/v1/products/`<br/>
`curl -H "Accept: application/json" "http://127.0.0.1:8000/api/v1/products" | jq .`<br/>

Display the product by ID<br/>
`GET /api/v1/product/{sku}`<br/>
`curl -H "Accept: application/json" "http://127.0.0.1:8000/api/v1/product/C99900217" | jq .`<br/>

Display ids of all products with same size. Can variate list of fields, ex: fields=sku,name<br/>
`GET /api/v1/products/?size={size}&fields=sku`<br/>
`curl -H "Accept: application/json" "http://127.0.0.1:8000/api/v1/products?size=38&fields=sku" | jq .`<br/>

Display all collections<br/>
`GET /api/v1/collections/`<br/>
`curl -H "Accept: application/json" "http://127.0.0.1:8000/api/v1/collections" | jq .`<br/>

Display all products of given collections<br/>
`GET /api/v1/collection/{id}/products`<br/>
`curl -H "Accept: application/json" "http://127.0.0.1:8000/api/v1/collection/dapper/products" | jq .`<br/>

## What did i use?
I used Laravel mostly because I wanted to meet myself with the framework. So since it my first project with it, maybe something is not completely in the spirit of Laravel. Firstly i user MySQL but when i started writing the installation instruction, I switched to sqlite because it easier for installation.<br/>
