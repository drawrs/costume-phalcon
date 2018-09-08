<?php

namespace Qodr\Controllers;

use Qodr\Controllers\ControllerBase;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->setView([
            "location" => "index/index"
        ]);
    }

}