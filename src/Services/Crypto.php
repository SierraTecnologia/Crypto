<?php

namespace SierraTecnologia\Crypto\Services;

use SierraTecnologia\Crypto\Encryption\CryptoEncrypter;
use SierraTecnologia\Crypto\Encryption\LaravelCrypto;

class Crypto
{
    /**
     * Make the value shareable.
     *
     * @return CryptoEncrypter
     */
    public static function shareable()
    {
        $key = getenv('APP_KEY');

        if (!$key) {
            $key = config('app.key');
        }

        return new CryptoEncrypter($key, $key);
    }

    /**
     * Encrypt using the Laravel Crypto.
     *
     * @param string $value
     *
     * @return string
     */
    public static function encrypt($value)
    {
        return (new LaravelCrypto())->encrypt($value);
    }

    /**
     * Decrypt using the Laravel Crypto.
     *
     * @param string $value
     *
     * @return string
     */
    public static function decrypt($value)
    {
        return (new LaravelCrypto())->decrypt($value);
    }

    /**
     * Generate a UUID.
     *
     * @return string
     */
    public static function uuid()
    {
        return (new LaravelCrypto())->uuid();
    }

    /**
     * Response if string is crupto
     *
     * @return string
     */
    public static function isCrypto($crypto)
    {
        if (strlen($crypto)<60) {
            return false;
        }

        if (strripos($crypto, '\\') !== false) {
            return false;
        }

        return true;
    }
}
