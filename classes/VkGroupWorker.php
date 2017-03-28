<?php

/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 23:50
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Api.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Storage.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/VkAuthorizer.php';

class VkGroupWorker
{
    private $access_token;
    private $error;

    public function getError() {
        return $this->error;
    }

    public function setToken($access_token) {
        $this->access_token = $access_token;
    }

    public function loadToken() {

        $storage = new Storage();
        $storage->loadData();
        $this->access_token = $storage->getToken();
    }

    public function getGroupName($groupId) {
        $group = Api::getInstance()->getGroupById($groupId);
        if(isset($group->error)) {
            $this->error = $group->error->error_msg;
            return false;
        }
        $response = $group->response;
        $name = $response[0]->name;
        return $name;
    }

    public function removeInactiveMembers($groupId, $test = 0) {
        $offset = 0;
        $limit = 1000;
        $response = Api::getInstance()->getGroupMembers($groupId, 'id_asc', '', $offset, $limit)->response;
        $totalCount = $response->count;
        $countLeft = $totalCount - $limit;
        $members = $response->users;        // list of IDs

        while($countLeft > 0) {
            $offset += $limit;
            $countLeft -= $limit;
            $response = Api::getInstance()->getGroupMembers($groupId, 'id_asc', '', $offset, $limit)->response;
            $members = array_merge($members, $response->users);
        }

        $offset = 0;
        $limit = 100;

        $removedUsers = [];
        $processedUsers = 0;

        while(!empty($members)) {
            $userIds = array_splice($members, $offset, $limit);
            $processedUsers += count($userIds);
            echo('members len: ' . count($members));

            if($processedUsers % 1000) {
                echo 'Processed users: ' . $processedUsers . '; Pause 3 secs...';
                sleep(3);
            }

            $response = Api::getInstance()->checkRemoveGroupUsers($userIds, $groupId, $this->access_token, $test);

            if(isset($response->error)) {  // auth probs
                if($response->error->error_code == 5) {
                    $vkAuth = new VkAuthorizer();
                    $vkAuth->redirectGetAuth();
                    return;
                } else {
                    die(var_dump($response->error));
                }
            }
            $response = $response->response;
            $removedUsers = array_merge($removedUsers, $response);
        }

        return $removedUsers;
    }
}