<?php 
require_once('vendor/autoload.php'); 
$user = \model\User::fetch_by_username('admin');
echo $user->get_display_name();
$user2 = \model\User::fetch_by_email('kushaldeveloper@gmail.com');
if ($user2->verify_password('hunter2')) { echo 'huge success!'; } else { echo 'defeat!'; }