<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 19/05/18
 * Time: 16:20.
 */

namespace Cheezykins\LaravelEncryptable\Test;

use Cheezykins\LaravelEncryptable\Test\Mocks\EncryptableModel;
use Illuminate\Support\Facades\Crypt;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class EncryptableTest extends MockeryTestCase
{
    public function testNonEncryptedUnchanged()
    {
        Crypt::shouldReceive('encrypt')
            ->never();

        $x = new EncryptableModel();
        $x->setAttribute('name', 'testing');
        $this->assertArrayHasKey('name', $x->getAttributes());
        $this->assertEquals('testing', $x->getAttributes()['name']);
    }

    public function testNonEncryptedDoesntDecrypt()
    {
        Crypt::shouldReceive('decrypt')
            ->never();

        $x = new EncryptableModel();
        $x->setAttribute('name', 'testing');
        $this->assertArrayHasKey('name', $x->getAttributes());
        $this->assertEquals('testing', $x->getAttributes()['name']);
        $this->assertEquals('testing', $x->getAttribute('name'));
    }

    public function testEncryptsAttribute()
    {
        Crypt::shouldReceive('encrypt')
            ->once()
            ->with('this is secret data!')
            ->andReturn('encrypted!');

        $x = new EncryptableModel();

        $x->setAttribute('securedata', 'this is secret data!');
        $this->assertArrayHasKey('securedata', $x->getAttributes());
        $this->assertEquals('encrypted!', $x->getAttributes()['securedata']);
    }

    public function testDecryptsAttribute()
    {
        Crypt::shouldReceive('encrypt')
            ->with('this is secret data!')
            ->andReturn('encrypted!');

        Crypt::shouldReceive('decrypt')
            ->once()
            ->with('encrypted!')
            ->andReturn('this is secret data!');

        $x = new EncryptableModel();

        $x->setAttribute('securedata', 'this is secret data!');
        $this->assertEquals('encrypted!', $x->getAttributes()['securedata']);
        $this->assertEquals('this is secret data!', $x->getAttribute('securedata'));
    }

    public function testDoesntEncryptNulls()
    {
        $x = new EncryptableModel();

        $x->setAttribute('securedata', null);
        $this->assertNull($x->getAttributes()['securedata']);
    }

    public function testDoesntDecryptNulls()
    {
        $x = new EncryptableModel();

        $x->setAttribute('securedata', null);
        $this->assertNull($x->getAttribute('securedata'));
    }

    public function testArrayDecryption()
    {
        Crypt::shouldReceive('encrypt')
            ->with('this is secret data!')
            ->andReturn('encrypted!');

        Crypt::shouldReceive('decrypt')
            ->with('encrypted!')
            ->andReturn('this is secret data!');

        $x = new EncryptableModel();

        $x->setAttribute('name', 'test name');
        $x->setAttribute('email', 'test@test.test');
        $x->setAttribute('securedata', 'this is secret data!');

        $array = $x->attributesToArray();

        $this->assertEquals('encrypted!', $x->getAttributes()['securedata']);
        $this->assertEquals('this is secret data!', $array['securedata']);
    }
}
