<?php

namespace SierraTecnologia\Crypto\Tests;

use Cheezykins\LaravelEncryptable\Test\Mocks\EncryptableModel;
use Orchestra\Testbench\TestCase;
use SierraTecnologia\Crypto\CryptoProvider;

require_once __DIR__.'/Mocks/EncryptableModel.php';

class EncryptableTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [CryptoProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set(
            'app.key',
            'base64:JZvB5yvsg7wRvIy8H6Yq1234567890abcdefghij='
        );
    }

    public function test_non_encrypted_attribute_is_unchanged(): void
    {
        $model = new EncryptableModel();
        $model->setAttribute('name', 'testing');

        $this->assertSame('testing', $model->getAttribute('name'));
    }

    public function test_encrypted_attribute_round_trips(): void
    {
        $model = new EncryptableModel();
        $model->setAttribute('securedata', 'this is secret data!');

        $this->assertNotSame('this is secret data!', $model->getAttributes()['securedata']);
        $this->assertSame('this is secret data!', $model->getAttribute('securedata'));
        $this->assertSame('this is secret data!', $model->toArray()['securedata']);
    }

    public function test_null_encrypted_attribute_remains_null(): void
    {
        $model = new EncryptableModel();
        $model->setAttribute('securedata', null);

        $this->assertNull($model->getAttribute('securedata'));
        $this->assertNull($model->toArray()['securedata']);
    }
}
