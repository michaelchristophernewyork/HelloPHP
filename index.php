<?php
require_once('vendor/autoload.php');
$user = \model\User::fetchByUsername('admin');
echo $user->getDisplayName() . PHP_EOL;
$user2 = \model\User::fetchByEmail('kushaldeveloper@gmail.com');
if ($user2->verifyPassword('hunter2')) {
    echo 'huge success!' . PHP_EOL;
} else {
    echo 'defeat!' . PHP_EOL;
}

if ($user->getUsername() == $user2->getUsername()) {
    echo 'both users are the same' . PHP_EOL;
} else {
    echo 'not the same at all' . PHP_EOL;
}

echo 'give them new line ' . PHP_EOL;

echo 'fin';