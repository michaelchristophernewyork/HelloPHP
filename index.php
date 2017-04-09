<?php
require_once('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache' => '',
));

$template = $twig->load('hello.html');
echo $template->render(array('the' => 'variables', 'go' => 'here'));