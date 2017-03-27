<?php
/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 21:44
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/VkAuthorizer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Storage.php';

if(isset($_GET['code'])) {

    $vk = new VkAuthorizer();
    $vk->setCode($_GET['code']);
    $vk->redirectGetToken();

} elseif(isset($_GET['access_token'])) {

    $token = $_GET['access_token'];
    $vk = new VkAuthorizer();
    $vk->setCode($token);
    $storage = new Storage();
    $storage->loadData()->setData('token', $token)->saveData();

} elseif(isset($_GET['error_description'])) {

    echo 'Get code error: ' . $_GET['error_description'];

} else {

    echo 'Get code error: unknown error.';

}