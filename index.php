<?php
require_once('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$users = [];
$pageTitle = 'hello, title';
$user1 = \model\User::fetchByUsername('admin');
array_push($users, $user, $pageTitle1);
echo $twig->render('hello.html', $users);