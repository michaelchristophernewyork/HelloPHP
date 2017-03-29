<?php 
require_once('vendor/autoload.php'); 
$user = \model\User::fetchByUsername('admin');
echo $user->getDisplayName();
$user2 = \model\User::fetchByEmail('kushaldeveloper@gmail.com');
if ($user2->verifyPassword('hunter2')) { echo 'huge success!'; } else { echo 'defeat!'; }