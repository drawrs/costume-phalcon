<?php

use Qodr\Bootstrap;

require dirname(__DIR__) . "/bootstrap/bootstrap.php";

$bootstrap = new Bootstrap();

echo $bootstrap->run();