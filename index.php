<?php
require_once('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$users = [];
$user1 = \model\User::fetchByUsername('admin');
array_push($users, $user1);
$user2 = \model\User::addUser('kus', $user1->getDisplayName(), 'hunter2', $user1->getEmail(), false);
array_push($user2);
echo $twig->render('hello.html', $users);
\model\User::removeUser('kus');