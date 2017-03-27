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
    private $token;

    public function redirectGetAuth() {

        global $config;

        if(!isset($config)) {
            echo 'Cannot load config!';
            return false;
        }

        $url = sprintf(
            '%s?client_id=%s&redirect_uri=%s&display=%s&scope=%s&response_type=%s&v=%s&revoke=%s',
            self::VK_OAUTH_CODE_URL,
            $config['vk_client_id'],
            $config['vk_auth_redirect'],
            'page', // either popup or mobile
            '262144',
            'token',
             '5.63',
            '0'
        );

        header('Location:' . $url, true);
        return true;
    }

    public function setToken($token) {
        $this->token = $token;
    }
}