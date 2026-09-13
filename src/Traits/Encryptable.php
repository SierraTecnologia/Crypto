<?php

namespace SierraTecnologia\Crypto\Traits;

use Crypto;

/**
 * Used to encrypt/decrypt Eloquent Model properties.
 * Any properties listed in the $encryptable array
 * will be automatically encrypted when set, and
 * decrypted when accessed, or when the model
 * is converted toJson() or toArray().
 *
 * Encryption is handled by the Crypto helper function, which
 * uses the cipher/key defined in config/app.php.
 *
 * Class Encryptable
 *
 * @package SC2
 * @author Ricardo Sierra <sierra.csi@gmail.com>
 */

trait Encryptable
{
    /**
     * Decrypt the column value if it is in the encrypted array.
     *
     * @param $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);
        if (in_array($key, $this->encrypted ?? [])) {
            $value = $this->decryptValue($value);
        }

        return $value;
    }

    /**
     * Decrypts a value only if it is not null and not empty.
     *
     * @param $value
     *
     * @return mixed
     */
    protected function decryptValue($value)
    {
        if ($value !== null && !empty($value)) {
            return Crypto::decrypt($value);
        }

        return $value;
    }

    /**
     * Set the value, encrypting it if it is in the encrypted array.
     *
     * @param $key
     * @param $value
     *
     * @return
     */
    public function setAttribute($key, $value)
    {
        if ($value !== null && in_array($key, $this->encrypted ?? [])) {
            $value = Crypto::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Retrieves all values and decrypts them if needed.
     *
     * @return mixed
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->encrypted ?? [] as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = $this->decryptValue($attributes[$key]);
            }
        }

        return $attributes;
    }
}
