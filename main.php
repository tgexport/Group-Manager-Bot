<?php
declare(strict_types=1);
use AnarchyService\Base;
use AnarchyService\SendRequest\Send;
require_once 'vendor/autoload.php';

$bot = new Base();
$bot = $bot->pollUpdates();
$text = $bot->result->message->text;
var_dump($text);
Send::sendMessage(116948493,'test');
