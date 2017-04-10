<?php
require_once('vendor/autoload.php');

$users = [];
$user1 = \model\User::fetchByUsername('admin');
$user2 = \model\User::addUser('kus', $user1->getDisplayName(), \model\User::hashPassword('hunter2'), 'billg@microsoft.com');
echo $user2->verifyPassword('hunter2');