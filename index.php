<?php
require_once('vendor/autoload.php');
$db = \model\Db::getInstance();
$settings = \model\Setting::fetch_all();
echo $settings["ldap_baseDN"];
echo  $settings["service_username"];
