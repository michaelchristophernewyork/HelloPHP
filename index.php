<?php
require_once('vendor/autoload.php');
$db = Db::getInstance();
$user1 = \model\User::fetchByUsername($db, 'admin');
echo $user1->getEmail();