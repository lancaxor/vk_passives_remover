<?php
/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 21:21
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/VkAuthorizer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Storage.php';

$storage = new Storage();
$storage->loadData();
$token = $storage->getToken();

if(empty($token)) {
    echo 'Authorization...';
    $vkAuth = new VkAuthorizer();
    $vkAuth->redirectGetAuth();
}

echo 'Okay, let`s work!';