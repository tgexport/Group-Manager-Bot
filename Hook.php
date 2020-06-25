<?php
declare(strict_types=1);
$message = file_get_contents("php://input");
$filename = "temp/.up_" . rand(0, 1000) . "" . time();
file_put_contents($filename, $message);
exec("php main.php $filename > /dev/null &");
