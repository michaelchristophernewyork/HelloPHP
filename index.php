<?php
require_once('vendor/autoload.php');
$db = \model\Db::getInstance();
$settings = \model\Setting::fetch_all();
foreach ($settings as $setting)
{
    foreach ($setting as $value)
    {
        echo $value . PHP_EOL;
    }
}
