<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://github.com/asseco-voice/art/blob/main/asseco_logo.png" width="500"></a></p>

# Custom fields

Purpose of this repository is to provide custom field support for any Laravel model. 

**Custom field** can be any field with which you wish to extend your model providing
a highly flexible model for additional fields, without the need to add new attributes 
to a DB model.

## Installation

Require the package with ``composer require asseco-voice/laravel-custom-fields``.
Service provider will be registered automatically.

## Setup

In order to use this repository the following must be done:

1. Each model which requires custom field support MUST use ``Customizable`` trait. 
1. Run ``php artisan migrate`` to migrate package tables
1. Run ``php artisan db:seed --class="Voice\CustomFields\Database\Seeders\PlainTypeSeeder"``
to seed mandatory data only. 
1. You may include ``CustomFieldPackageSeeder`` within your ``DatabaseSeeder``
seeders and have it seed mandatory data in all environments or seed all other dummy data in
non-production environments. 

## Model breakdown

A single custom field can be one of 3 types:

- ``Plain`` - standard single-value properties like int/string/date etc.
- ``Remote`` - multiple values which can be fetched from an arbitrary endpoint. Mappings can be provided in `localKey => remoteKey` format 
to provide display helpers for front end. 
- ``Selection`` - provide a set of predefined values from which you can select one or many.  

It can have:

- ``Validation`` - providing regex validation. Can be a nullable property from custom field perspective.
- ``Relationship`` - providing a parent-child M:M relationship.

// TODO: ER model
