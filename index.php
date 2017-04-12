<?php
require_once('vendor/autoload.php');
$db = \model\Db::getInstance();
$user1 = \model\User::fetchByUsername($db, 'admin');
echo $user1->getEmail();