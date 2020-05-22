<?php

namespace SierraTecnologia\Crypto\Encryption;

class LaravelCrypto extends CryptoEncrypter
{
    /**
     * Construct the Laravel version of the LaracogsEncrypter
     * using the app key and the session ID.
     */
    public function __construct()
    {
        $app_key = \Illuminate\Support\Facades\Config::get('app.key') ?: 'unsafe';
        $auth_key = auth()->id() ?: 0;

        parent::__construct($app_key, $auth_key);
    }
}
