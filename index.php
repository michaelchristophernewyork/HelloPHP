<?php
require_once('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$users = [];
$user1 = \model\User::fetchByUsername('admin');
array_push($users, $user1);
$user2 = \model\User::fetchByUsername('admin');
array_push($users, $user1);

echo $twig->render('hello.html', $users);