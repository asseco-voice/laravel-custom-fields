# Custom fields

Purpose of this repository is to provide support custom fields
for any Laravel model. 

**Custom fields** is an entity representing a logical container
for a collection of other entities which require ACL to be 
enforced on.

## Installation

Require the package with 
``composer require asseco-voice/laravel-custom-fields``.
Service provider for Laravel will be installed automatically.

## Usage

In order to use this repository the following must be done:

1. Each model which requires container
support should use ``Containable`` trait. 
2. Run ``php artisan asseco-voice:custom-fields`` which
will generate migrations for models having `Containable` trait. 
3. Run ``php artisan migrate`` to migrate generated
migrations

**Additional notes**: migrations will generate foreign keys as 
expected by Laravel standards. For anything custom, after running
``php artisan asseco-voice:custom-fields`` edit generated migrations,
but **do not** change table names, those are named as Laravel 
standard for pivot tables. 


## Configuration

The stock configuration looks like this and most probably should never
be changed, but if you ever need to override it:

```
'containers' => [
    /**
     * Path to Laravel models. This does not recurse in folders
     */
    'models_path'     => app_path(),
    /**
     * Namespace for Laravel models.
     */
    'model_namespace' => 'App\\',
],
```
