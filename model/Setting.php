<?php

namespace model;

class Setting
{
    public $name;
    public $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function fetch_all()
    {
        $settings = [];
        $db = Db::getInstance();
        $q = $db->query('SELECT settingname, settingvalue FROM settings');
        foreach ($q->fetchAll() as $row) {
            $settings[$row['settingname']] = $row['settingvalue'];
        }
        return $settings;
    }

    public static function find($settingname)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT settingname, settingvalue FROM settings WHERE settingname = :settingname');
        $req->execute(array('settingname' => $settingname));
        $setting = $req->fetch();
        $this_setting = new Setting($setting['settingname'], $setting['settingvalue']);
        $this_setting->apply_rules();
        return $this_setting;
    }

    private function apply_rules()
    {
        $this->set_default_theme();
    }

    private function set_default_theme()
    {
        if ($this->name == "theme" && $this->value == "") {
            //TODO: Make default theme configurable?
            $this->value = "default";
        }
    }

    public static function update($settingname, $settingvalue): bool
    {
        $db = Db::getInstance();
        $req = $db->prepare('UPDATE settings SET settingvalue = :settingvalue WHERE settingname = :settingname');
        $req->bindParam(':settingname', $settingname, \PDO::PARAM_STR);
        $req->bindParam(':settingvalue', $settingvalue, \PDO::PARAM_STR);
        $req->execute();
        return true;
    }

    public function get_value()
    {
        return $this->value;
    }

    function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->value;
    }
}
