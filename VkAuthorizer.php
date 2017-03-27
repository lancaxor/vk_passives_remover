<?php

/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 21:33
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

class VkAuthorizer
{
    const VK_OAUTH_CODE_URL = 'https://oauth.vk.com/authorize';
    const VK_OAUTH_TOKEN_URL = 'https://oauth.vk.com/access_token';
    private $code;
    private $token;

    public function redirectGetCode() {

        global $config;

        if(!isset($config)) {
            echo 'Cannot load config!';
            return false;
        }

        $url = sprintf(
            '%s?client_id=%s&display=%s&redirect_uri=%s&scope=%s&response_type=%s&v=%s',
            self::VK_OAUTH_CODE_URL,
            $config['vk_client_id'],
            'page', // either popup or mobile
            $config['vk_auth_redirect'],
            '262144',
            'code',
            '5.63'
        );
        header('Location:' . $url, true);
        return true;
    }

    public function redirectGetToken() {

        global $config;

        if(!isset($config)) {
            echo 'Cannot load config!';
            return false;
        }

        if(empty($this->code)) {
            echo 'Wrong code! Follow main page and get the code!';
            return false;
        }

        $url = sprintf(
            '%s?client_id=%s&client_secret=%s&redirect_uri=%s&code=%s',
            self::VK_OAUTH_TOKEN_URL,
            $config['vk_client_id'],
            $config['vk_client_secret'],
            $config['vk_auth_redirect'],
            $this->code
        );

        header('Location:' . $url, true);
        return true;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setToken($token) {
        $this->token = $token;
    }

}