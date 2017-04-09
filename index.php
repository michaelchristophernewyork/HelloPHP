<?php
require_once('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

echo $twig->render('hello.html', array(
    'a_variable' => 'Welcome to Twig!',
    'navigation' => array(
        'header' => array (
            'href' => 'https://www.google.com',
            'caption' => 'Google dot com'
        )
    ),
));