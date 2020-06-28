<?php
declare(strict_types=1);
$input = file_get_contents("php://input");
$obj = json_encode($input);
$type = $obj->message->chat->type;
if (function_exists('exec')) {
    $temp = "temp/.up_" . rand(0, 1000) . "" . time();
    file_put_contents($temp, $input);
    if ($type == 'private') exec("php private.php $temp > /dev/null &");
    elseif ($type == 'channel') exec("php channel.php $temp > /dev/null &");
    elseif ($type == 'supergroup' || 'group') exec("php group.php $temp > /dev/null &");
} /*not recommended beater way is use exec function */
else {
    if ($type == 'private') require_once 'private.php';
    elseif ($type == 'channel') require_once 'channel.php';
    elseif ($type == 'supergroup' || 'group') require_once 'group.php';
}