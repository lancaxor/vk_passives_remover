<?php

/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 28.03.2017
 * Time: 0:08
 */
class Api
{
    private static $instance;
    const VK_API_URL = 'https://api.vk.com/method';

    private function __construct() {}

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Api();
        }
        return self::$instance;
    }

    private function getMethodUrl($method) {
        return self::VK_API_URL . '/' . $method;
    }

    /**
     * @param $groupId          string  ID or screen name of the community.
     * @param string $sort      string  Sort order. Available values: id_asc, id_desc, time_asc, time_desc.
     *                                  time_asc and time_desc are available only if the method is called by the group's moderator.
     * @param string $fields    string  List of additional fields to be returned.
     * @param int $offset       string  Offset needed to return a specific subset of community members.
     * @param int $count        string  Number of community members to return. MAX = 1000, default = 1000
     * @param null $filter      string  friends|unsure
     * @param string $version   string  current: 5.63
     *
     * @return object|false
     *
     * @link https://vk.com/dev/groups.getMembers
     */
    public function getGroupMembers($groupId, $sort = 'id_asc', $fields = '', $offset = 0, $count = 1000, $filter = null, $version = '5.63') {
        $data = [
            'group_id'  => $groupId,
            'sort'      => $sort,
            'offset'    => $offset,
            'count'     => $count,
            'fields'    => $fields,
            'filter'    => $filter
        ];

        $url = $this->getMethodUrl('groups.getMembers');

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            return false;
        }

        return json_decode($result);
    }

    /**
     * @param $groupId
     * @return bool|mixed
     */
    public function getGroupById($groupId) {

        if(is_array($groupId)) {
            $groupId = implode(',', $groupId);
            $groupIdField = 'group_ids';
        } else {
            $groupIdField = 'group_id';
        }
        $data = [
            $groupIdField   => $groupId,
        ];

        $url = $this->getMethodUrl('groups.getById');

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            return false;
        }

        return json_decode($result);
    }

    /**
     * @param $userIds array
     * @param $groupId string
     * @param $access_token string
     * @param $test integer Either 1 or 0
     * @return bool|object
     */
    public function checkRemoveGroupUsers($userIds, $groupId, $access_token, $test = 0) {
        $data = [
            'user_ids'      => implode(',', $userIds),
            'group_id'      => $groupId,
            'access_token'  => $access_token,
            'test'          => $test
        ];

        $url = $this->getMethodUrl('execute.checkRemoveGroupUsers');

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            return false;
        }

        return json_decode($result);
    }
}