<?php

namespace Cheezykins\LaravelEncryptable\Test\Mocks;

use Cheezykins\LaravelEncryptable\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class EncryptableModel extends Model
{
    use Encryptable;

    protected $encrypted = [
        'securedata',
    ];
}
