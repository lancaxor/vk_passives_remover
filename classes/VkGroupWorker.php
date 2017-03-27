<?php

/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 23:50
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/Api.php';

class VkGroupWorker
{
    private $access_token;

    public function setToken($access_token) {
        $this->access_token = $access_token;
    }

    public function getMembers($groupId) {
        $data = Api::getInstance()->getGroupMembers($groupId);
    }
}