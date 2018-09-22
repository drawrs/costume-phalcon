<?php

namespace Qodr\Controllers\Base;

use Phalcon\Mvc\Controller;

abstract class ControllerBase extends Controller
{

    public function initialize()
    {
        $this->view->javascript = [];
        $this->view->stylesheet = [];
    }

    protected function setView(array $config = [])
    {
       foreach ($config as $key => $value) {
           switch ($key) {
                case 'location':
                    $this->view->pick($value);
                    break;
                case 'template':
                    $this->view->setMainView($value);
                    break;
                case 'variable':
                    $this->view->setVars($value);
                    break;
                case 'javascript':
                    $this->view->javascript = $value;
                    break;
                case 'stylesheet':
                    $this->view->stylesheet = $value;
                    break;
                case 'render':
                    if ($value) {
                        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
                    }
                    break;
            }
       } 
    }
    
}
