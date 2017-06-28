LaraMod Admin Products 0.* Alpha
----------------------------
LaraMod is a modular Laravel based CMS.
https://github.com/LaraModulus

**WARNING: Until v1 there will be no backward compatibility and some versions may require migrate:refresh** 

Installation
---------------
```
composer require laramod\admin-products
```
 **config/app.php**
 
```php 
'providers' => [
    ...
    LaraMod\Admin\Products\AdminProductsServiceProvider::class,
]
```
**Publish migrations**
```
php artisan vendor:publish --tag="migrations"
```
**Run migrations**
```
php artisan migrate
```

In `config/admincore.php` you can edit admin menu

**DEMO:** http://laramod.novaspace.eu/admin
```
user: admin@admin.com
pass: admin