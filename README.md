# Custom fields

Purpose of this repository is to provide custom field support for any Laravel model. 

**Custom field** can be any field with which you wish to extend your model providing
a highly flexible model for additional fields, without the need to constantly add
new attributes to a DB model.

## Installation

Require the package with ``composer require asseco-voice/laravel-custom-fields``.
Service provider will be registered automatically, so no additional setup is needed.

## Usage

In order to use this repository the following must be done:

1. Each model which requires custom field support should use ``Customizable`` trait. 
2. Run ``php artisan asseco-voice:custom-fields`` which will generate migrations for 
models having `Customizable` trait (M:M pivot tables). 
3. Run ``php artisan migrate`` to migrate generated migrations

**Additional notes**: 
- migrations will generate foreign keys as expected by Laravel standards. 
For anything custom, after running ``php artisan asseco-voice:custom-fields`` 
edit generated migrations, but **do not** change table names, those are named as Laravel 
standard for pivot tables. 

Adding custom fields and their respected values can be done through exposed endpoints
which are standard Laravel CRUD actions:

- ``api/custom-fields``
- ``api/custom-field-types``
- ``api/custom-field-validations``

## ER model

Warning, explicit graphics ahead:

```
model ----- pivot
              |
              |
        custom fields ------- relations
          |       |
          |       |
        types  validation
```

A single ``CustomField`` can have its `Type` (can be anything, but generally thought of as
being a string representation of a database field type for additional front end classification
if needed), `Validation` (providing any form of validation string, most probably regex) and
``Relation`` (custom field hierarchy pivot table where a single custom field parent can have multiple
children/descendants).

Connecting any model to a custom field will be done through a pivot table which besides relation
to the ``custom_fields`` table has also values split to several value columns (`value_text`, `value_number`...)
for more performant table searching.
