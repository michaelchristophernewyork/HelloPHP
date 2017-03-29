<?php

namespace model;
class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

// db
Config::write('db.host', 'stampy.db.elephantsql.com');
Config::write('db.port', '5432');
Config::write('db.basename', 'fzntsbqt');
Config::write('db.user', 'fzntsbqt');
Config::write('db.password', 'CtkEjRcQCIEQXh276-mINCY5h_g6yNUE');
