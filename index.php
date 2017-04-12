<?php
require_once('vendor/autoload.php');
$db = \model\Db::getInstance();
$user1 = \model\User::fetchByUsername($db, 'admin');
echo $user1->getEmail();
$user2 = new \model\User();
$user2->addUserLdap('khada100');
echo $user2->getEmail();