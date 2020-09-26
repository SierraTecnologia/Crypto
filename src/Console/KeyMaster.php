<?php

namespace SierraTecnologia\Crypto\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class KeyMaster extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'crypto:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crypto will generate a new encryption key for your app';

    /**
     * Set the application key in the environment file.
     *
     * @param  string $key
     * @return void
     */
    protected function setKeyInEnvironmentFile($key)
    {
        preg_match('/APP_KEY=?(.*)/', file_get_contents(base_path('.env')), $match);
        file_put_contents(
            base_path('.env'), str_replace(
                'APP_KEY='.$match[1],
                'APP_KEY='.$key,
                file_get_contents(base_path('.env'))
            )
        );
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey(): string
    {
        return 'base64:'.base64_encode(random_bytes(32));
    }
}
