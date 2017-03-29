<?php

namespace model;
class Db
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            $pdoOptions[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
            $dbName = 'pgsql:host=' . Config::read('db.host') . 
            ';dbname=' . 
            Config::read('db.basename');
            self::$_instance = new \PDO($dbName, Config::read('db.user'), Config::read('db.password'), $pdoOptions);
        }
        return self::$instance;
    }

    private function __clone()
    {
    }
}
