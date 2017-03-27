<?php

/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 27.03.2017
 * Time: 22:26
 */
class Storage
{
    private $data = [];
    private $filePath;
    private $file;

    public function __construct() {
        $this->filePath = 'data.dat';
        $this->loadData();
    }

    public function loadData() {
        if(!file_exists($this->filePath)) {
            $this->file = fopen($this->filePath, 'w');
            fclose($this->file);
            $this->data = [];
            return $this;
        }
        $json = file_get_contents($this->filePath);
        if(empty($json)) {
            $this->data = [];
            return $this;
        }
        try {
            $this->data = json_decode($json, true);
        } catch(Exception $e) {
            $this->data = [];
            return $this;
        }
        return $this;
    }

    public function setData($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }

    public function saveData() {
        $json = json_encode($this->data);
        file_put_contents($this->filePath, $json);
        return $this;
    }

    // -------- special data functions

    public function getToken() {
        return (empty($this->data['token']) ? null : $this->data['token']);
    }
}