<?php

namespace Qodr\Controllers;

use Qodr\Controllers\Base\ControllerBase;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->setView([
            "location" => "index/index"
        ]);
    }

}