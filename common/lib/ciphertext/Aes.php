<?php

namespace common\lib\ciphertext;

/**
 * AesCbc 加解密
 * Class AesCbc pcsk5/7
 * @package common\lib\ciphertext
 */
class Aes
{
    public $iv='1234567890123456';

    /**
     * aes cbc 加密 go通用
     * @param $data
     * @param $key 长度为16
     * @return string
     * @throws \Exception
     */
    public function encryptCbc($data, $key) {
        if(empty($key)){
            throw new \Exception('encrypt key is null');
        }
        $data =  openssl_encrypt($data, 'aes-128-cbc', $key, 0,$this->iv);
        return $this->urlsafeB64Encode($data);
    }

    /**
     * aes cbc解密 go通用
     * @param $data
     * @param $key 长度为16
     * @return string
     * @throws \Exception
     */
    public function decryptCbc($data, $key) {
        if(empty($key)){
            throw new \Exception('encrypt key is null');
        }

        return openssl_decrypt($this->urlsafeB64Decode($data), 'aes-128-cbc', $key, 0,$this->iv);
    }

    /**
     * aes ecb 加密 go 未测试
     * @param $data
     * @param $key 长度为16
     * @return string
     * @throws \Exception
     */
    public function encryptEcb($data, $key) {
        if(empty($key)){
            throw new \Exception('encrypt key is null');
        }
        $data =  openssl_encrypt($data, 'aes-128-ecb', $key, 0);
        return $this->urlsafeB64Encode($data);
    }

    /**
     * aes ecb 加密 go 未测试
     * @param $data
     * @param $key 长度为16
     * @return string
     * @throws \Exception
     */
    public function decryptEcb($data, $key) {
        if(empty($key)){
            throw new \Exception('encrypt key is null');
        }

        return openssl_decrypt($this->urlsafeB64Decode($data), 'aes-128-ecb', $key, 0);
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public function urlsafeB64Decode($input)
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public function urlsafeB64Encode($input)
    {
        return \str_replace('=', '', \strtr(\base64_encode($input), '+/', '-_'));
    }

}