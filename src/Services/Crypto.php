<?php

namespace SierraTecnologia\Crypto\Services;

use SierraTecnologia\Crypto\Encryption\CryptoEncrypter;
use SierraTecnologia\Crypto\Encryption\LaravelCrypto;

class Crypto
{

    /**
     * Instancia de Criptografia do Usuario
     *
     * @return CryptoEncrypter
     */
    public static function crypto()
    {
        return new LaravelCrypto();
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
        return (self::crypto())->encrypt($value);
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
        if (!self::isCrypto($value)) {
            return $value;
        }
        return (self::crypto())->decrypt($value);
    }

    /**
     * Generate a UUID.
     *
     * @return string
     */
    public static function uuid()
    {
        return (self::crypto())->uuid();
    }


    
    /**
     * Make the value shareable.
     *
     * @return CryptoEncrypter
     */
    public static function shareable()
    {
        $key = getenv('APP_KEY');

        if (!$key) {
            $key = \Illuminate\Support\Facades\Config::get('app.key');
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
    public static function shareableEncrypt($value)
    {
        return (self::shareable())->encrypt($value);
    }

    /**
     * Decrypt using the Laravel Crypto.
     *
     * @param string $value
     *
     * @return string
     */
    public static function shareableDecrypt($value)
    {
        return (self::shareable())->decrypt($value);
    }








    /**
     * Response if string is crupto
     *
     * @return string
     */
    public static function isCrypto(string $crypto)
    {
        if (strlen($crypto)<50) {
            return false;
        }

        if (strripos($crypto, '\\') !== false) {
            return false;
        }

        return true;
    }

    public static function url_encode($value)
    {
        return self::urlEncode($value);
    }
    public static function urlEncode($value)
    {
        return self::shareableEncrypt($value);
    }

    public static function url_decode($value)
    {
        return self::urlDecode($value);
    }
    public static function urlDecode($value)
    {
        return self::shareableDecrypt($value);
    }
}
