<?php
require_once('vendor/autoload.php');
echo PHP_EOL;
$user = \model\User::fetchByUsername('admin');
echo PHP_EOL;
echo $user->getDisplayName();
echo PHP_EOL;
$user2 = \model\User::fetchByEmail('kushaldeveloper@gmail.com');
echo PHP_EOL;
if ($user2->verifyPassword('hunter2')) { echo 'huge success!'; } else { echo 'defeat!'; }

if ($user->getUsername() == $user2->getUsername()) {
    echo PHP_EOL;
    echo 'both users are the same';
    echo PHP_EOL;
} else {
    echo 'not the same at all';
    echo PHP_EOL;
}

echo 'give them new line ';
echo PHP_EOL;