<?php
require_once('vendor/autoload.php');
$db = \model\Db::getInstance();
$settings = \model\Setting::fetch_all();
$user2 = new \model\User();
$user2->addUserLdap('khada100', $settings);
echo $user2->getEmail();