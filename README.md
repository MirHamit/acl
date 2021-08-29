# MirHamit/ACL


[![Latest Version on Packagist](https://img.shields.io/packagist/v/vendor_slug/package_slug.svg?style=flat-square)](https://packagist.org/packages/mirhamit/acl)
[![Total Downloads](https://img.shields.io/packagist/dt/vendor_slug/package_slug.svg?style=flat-square)](https://packagist.org/packages/mirhamit/acl)

---
This package can be used to moderate roles and permissions of users in laravel application

## Installation

open terminal and cd to your project root folder

install laravel :
```bash
laravel new laravel-acl-package
cd laravel-acl-package
```

setup your laravel auth [Laravel Docs](https://laravel.com/docs/8.x/authentication)

we use laravel 8 [Starter Pack](https://laravel.com/docs/8.x/starter-kits#laravel-breeze) and breeze in this section

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
```

Install this package with composer
```bash
composer require mirhamit/acl
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="MirHamit\acl\ACLServiceProvider" --tag="migrations"
```

And publish language files for customize language
```bash
php artisan vendor:publish --provider="MirHamit\acl\ACLServiceProvider" --tag="lang"
```

Or publish both language and migrations
```bash
php artisan vendor:publish --provider="MirHamit\acl\ACLServiceProvider"
```
And run the migration
```bash
php artisan migrate
```

---
## Usage
Add HasPermission to your user model
```php
use MirHamit\ACL\HasPermissions;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions;
...
}
```

You can add multiple roles in `roles` table and permissions in `permissions` table and bind permission to role in `permission_role` table

You can use middleware:
`test-permission` is a permission bound to `test-role` and bound to logged in user
```php
Route::get('test', function () {
    return "salam";
})->middleware('role:test-role, test-permission');
```

Or you can use it for only permission bounded for user
```php
Route::get('test', function () {
    return "salam";
})->middleware('permission: test-permission');
```
Or you can use it from Laravel Blade
```php
@role
// user has role and can access to this section
@endrole
```

---
## Testing
```bash
As Soon As Possible
```

---
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

---
## Security Vulnerabilities

Please review and check security vulnerabilities and report them in issues section.

---
## Credits

- [Həmid Musəvi](https://github.com/mirhamit)
- [All Contributors](../../contributors)

---
## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
