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
    BdMehedi\LaravelPermission\LaravelPermissionServiceProvider::class,
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

#### Assign roles and permissions to User
```php
$user->assignRole('admin');
$user->givePermissionTo('create-user');

//or your can assign permissions by providing an array
$user->assignRole(['admin', 'user']);
$user->givePermissionTo(['create-user']);
```

#### Withdraw roles and permissions from User
```php
// Single permission or role
$user->revokeRole('admin');
$user->withdrawPermissionTo('create-user');

// Or array of permissions or roles
$user->revokeRole(['admin']);
$user->withdrawPermissionTo(['create-user']);
```

#### Give permissions to Role
```php
$role->givePermissionTo('create-user');

//or an array or permissions
$role->givePermissionTo(['create-user']);
```

#### Withdraw permissions from Role
```php
$role->withdrawPermissionTo('create-user');

//or array or permissions
$role->givePermissionTo(['create-user']);
```

#### Checking permissions
You can check a user's permission with Laravel's default can function.
```php
$user->can('create-user');
```

Or you can check with hasPermissionTo
```php
$user->hasPermissionTo('create-user');
```

#### Checking roles
```php
$user->hasRole('admin');

// or check at least one role from an array of roles:
$user->hasRole(['admin', 'user']);
```

## Middleware
### Default middleware
You can use the laravel default middleware can provided by ```\Illuminate\Auth\Middleware\Authorize::class```
```php
Route::middleware('can:create-user')->group(function () {
    
});
```

### Package middleware
If you can check at least one premission of many you can use the ```allow``` middleware
```php
Route::middleware('allow:create|view|delete')->group(function () {
    
});
```
if you have another guard you can user
```php
Route::middleware('allow:create|view|delete,guardName')->group(function () {
    
});
```


## Contribution Guide

This is still in beta, though I have confidence that it will work as expected.
You can contribute by reporting bugs, fixing bugs, reviewing pull requests, and more ways.
Go to the [**issues**](https://github.com/bdmehedi/laravel-permission/issues) section, and you can start working on an issue immediately.
If you want to add or fix something, open a pull request.


