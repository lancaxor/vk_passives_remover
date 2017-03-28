<?php
/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 28.03.2017
 * Time: 21:55
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/VkGroupWorker.php';

if(isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_vk_group':
            if(!isset($_GET['group_id'])) {
                break;
            }
            $groupId = $_GET['group_id'];
            $vk = new VkGroupWorker();
            $result = $vk->getGroupName($groupId);
            if($result === false) {
                $responseData = ['error' => $vk->getError()];
            } else {
                $responseData = ['group_name' => $result];
            }
            echo(json_encode($responseData));
            break;
        default:
            break;
    }
}