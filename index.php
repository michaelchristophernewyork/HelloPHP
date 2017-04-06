<?php
require_once('vendor/autoload.php');
echo '\r\n';
$user = \model\User::fetchByUsername('admin');
echo '\r\n';
echo $user->getDisplayName();
echo '\r\n';
$user2 = \model\User::fetchByEmail('kushaldeveloper@gmail.com');
echo '\r\n';
if ($user2->verifyPassword('hunter2')) { echo 'huge success!'; } else { echo 'defeat!'; }

if ($user->getUsername() == $user2->getUsername()) {
    echo '\r\n';
    echo 'both users are the same';
    echo '\r\n';
} else {
    echo 'not the same at all';
    echo '\r\n';
}

echo 'give them new line ';
echo '\r\n';