<?php
declare(strict_types=1);
use AnarchyService\Base;
use AnarchyService\SendRequest\Send;
require_once 'vendor/autoload.php';

$bot = new Base();
$bot = $bot->getWebhookUpdates();
$text = $bot->result->message->text;
var_dump($text);
if ($text == 'ali') Send::sendMessage(116948493,'test');
