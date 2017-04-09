<?php
require_once('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
// setup some variables
$pageTitle = 'Suit Up!';
$products = array(
    new Product('Serious Businessman', 'formal.png'),
    new Product('Penguin Dress', 'dress.png'),
    new Product('Sportstar Penguin', 'sports.png'),
    new Product('Angel Costume', 'angel-costume.png'),
    new Product('Penguin Accessories', 'swatter.png'),
    new Product('Super Cool Penguin', 'super-cool.png'),
);
echo $twig->render('hello.html', array(
    'pageTitle' => 'Welcome to Penguins R Us!',
    'products' => array(
        'Tuxedo',
        'Bow tie',
        'Black Boxers',
    ),
));