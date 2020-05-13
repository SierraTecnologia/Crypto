# Crypto

**Crypto** - Powerful and elegant cryptography tools for Laravel and Lumen

[![Build Status](https://travis-ci.org/SierraTecnologiaInc/Crypto.svg?branch=master)](https://travis-ci.org/SierraTecnologiaInc/Crypto)
[![Maintainability](https://api.codeclimate.com/v1/badges/7577ab93d33bf9b69605/maintainability)](https://codeclimate.com/github/SierraTecnologiaInc/Crypto/maintainability)
[![Packagist](https://img.shields.io/packagist/dt/sierratecnologia/crypto.svg?maxAge=2592000)](https://packagist.org/packages/sierratecnologia/crypto)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](https://packagist.org/packages/sierratecnologia/crypto)

The Cryptograpy toolset Crypto provides a collection of methods for handy, encryption, decryption, uuid generating, app key generating and more.

##### Author(s):
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), mattlantz at gmail dot com)

## Requirements

1. PHP 5.6+
2. OpenSSL
3. Laravel 5.3+ or Lumen

* For Lumen you must enable Facades: `$app->withFacades()`

----

### Installation

Start a new Laravel project:
```php
composer create-project laravel/laravel your-project-name
```

Then run the following to add Crypto
```php
composer require "sierratecnologia/crypto"
```

## Documentation

[https://docs.sierratecnologia.ca/others/crypto](https://docs.sierratecnologia.ca/others/crypto)

## License
Crypto is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
# Laravel Encryptable Trait

Provides a trait to decrypt or encrypt values in a Laravel model.

## Usage

Add the trait to your model, define the ```$encrypted``` array and fields in it will be encrypted and decrypted on the fly by default.

```php
namespace App;

use Cheezykins\LaravelEncryptable\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model {
    use Encryptable;

    protected $encrypted = [
        'email'
    ];
}
```

Then in usage:

```php
$model = new MyModel();
$model->name = 'Chris';
$model->email = 'chris@test.com';
$model->save();
```

The email field in the database will now be the encrypted value.

```eyJpdiI6IkRZS0lOUlwvR29MbU4zN1diYzl2ZCtnPT0iLCJ2YWx1ZSI6IldEYzVUajlUcDdvVHE0M0kxdForNlE9PSIsIm1hYyI6ImY1MzQ2ZWYwNTNkZDI2YTY2MDgyMmVjZmU3MmI0MGU0NTNmMmU4NWE4OGFmYzZhYTJlYzczMWU1YTdmNzNjYjQifQ==```

When retrieved, the data will be automatically decrypted for you.

```php
$model = MyModel::find(1);
echo $model->email;
```
```> "chris@test.com"```

Same for other ways of accessing model data.

```php
$model = MyModel::find(1);
return response()->json($model->toArray());
```

```json
{
  "id": 1,
  "name": "Chris",
  "email": "chris@test.com"
}
```

**WARNING**

The encrypted value is stored based on your Laravel APP_KEY using the algorithm defined in your config/app.php cipher setting. If your application key is changed or lost there is **no way** to retrieve the data.

## Installation

require the project and use it. There is no service provider as it is not needed.

```composer require cheezykins/laravel-encryptable```