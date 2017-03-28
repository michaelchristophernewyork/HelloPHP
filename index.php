<?php 
require_once('vendor/autoload.php'); 
$user = \model\User::fetch_by_username('admin');
echo $user->get_username();
