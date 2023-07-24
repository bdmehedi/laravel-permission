![Laravel Role & Permission](https://banners.beyondco.de/Laravel%20Permission.png?theme=light&packageManager=composer+require&packageName=bdmehedi%2Flaravel-permission&pattern=architect&style=style_1&description=A+Laravel+package+to+manage+user%27s+roles+and+permissions&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# laravel-permission
This is a Laravel package that allows you to manage roles and permissions in any Laravel project with minimal database queries and a small number of loaded models. It is a highly optimized package

## Installation, configuration, and uses instructions

install via <b>composer</b>
```shell
composer require bdmehedi/laravel-permission
```

The <b>service provider</b> will be registered automatically. If you want, you may add it manually.
```code
'providers' => [
    // ...
    Spatie\Permission\PermissionServiceProvider::class,
];
```

<b>Publish</b> the migrations and edit as your need
```shell
php artisan vendor:publish --tag=laravel_permission_migration
```

<b>Run the migrations</b>. After publishing the migrations and configuring them as your need you have to create database tables by running the command bellow: 
```shell
php artisan migrate
```

#### Add the HasPermissions traits to User model(s)
```php
use HasPermissions
```

### Uses
<b>Create Role and Permission</b>
```php
use BdMehedi\LaravelPermission\Models\Permission;
use BdMehedi\LaravelPermission\Models\Role;


$role = Role::create(['role' => 'admin']);
$permission = Permission::create(['name' => 'create-user', 'group' => 'user']);  //the group is optional
```

##### Assign roles and permissions to User
```php
$user->assignRole(['admin']);
$user->givePermissionTo(['create-user']);
```

#### Withdraw roles and permissions from User
```php
$user->revokeRole(['admin']);
$user->withdrawPermissionTo(['create-user']);
```

##### Give permissions to Role
```php
$role->givePermissionTo(['create-user']);
```

#### Withdraw permissions from Role
```php
$role->withdrawPermissionTo(['create-user']);
```

