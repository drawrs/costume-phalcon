<?php

namespace Qodr;

use Phalcon\Mvc\View;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher as MvcDi;
use Phalcon\Cli\Dispatcher as CliDi;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Phalcon\Mvc\Application;
use Phalcon\Security;
use Qodr\Utils\ViewHelper;
use Qodr\Utils\TagHelper;

class Bootstrap
{

    private $di;

    public function __construct()
    {
        $this->installation();
    }
    
    public function run()
    {
        $this->di->getSession();
        $application = new Application($this->di);
        if (env('APP_MINIFY')) {
            $result = str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
        } else {
            $result = $application->handle()->getContent();
        }
        
        return $result;
    }

    private function installation()
    {
        $di = new FactoryDefault();

        $this->setConfig($di);
        $this->setUrls($di);
        $this->setView($di);
        $this->setDb($di);
        $this->setFlash($di);
        $this->setFlashSession($di);
        $this->setSession($di);
        $this->setRouter($di);
        $this->setHelper($di);

        $this->di = $di;
    }

    private function setConfig($di)
    {
        $di->setShared('config', function () {
            return include BASE_DIR . "config/config.php";
        });
    }

    private function setUrls($di)
    {
        $di->setShared('url', function () {
            $config = container()->getConfig();
            $url    = new UrlResolver();
            
            $url->setBaseUri($config->application->baseUri);
            
            return $url;
        });
    }

    private function setView($di)
    {
        $di->setShared('view', function () {
            $config = container()->getConfig();
            $view   = new View();
            
            $view->setDI(container());
            $view->setViewsDir($config->application->viewsDir);
            $view->setMainView('template/base');
            
            $view->registerEngines([
                '.volt' => function ($view) {
                    $config  = $this->getConfig();
                    $volt    = new VoltEngine($view, $this);
                    $setting = [
                        'compiledPath'      => $config->application->cacheViewDir,
                        'compiledSeparator' => '_',
                        'compileAlways'     => (env('APP_COMPILE')) ? true : false
                    ];
                    $volt->setOptions($setting);
        
                    return $volt;
                },
                '.phtml' => PhpEngine::class
        
            ]);
        
            return $view;
        });
    }

    private function setDb($di)
    {
        $di->setShared('db', function () {
            $config = container()->getConfig();
            $params = [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset
            ];

            return new Database($params);
        });
    }

    private function setFlash($di)
    {
        $di->set('flash', function () {
            return new FlashDirect([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });
    }

    private function setFlashSession($di)
    {
        $di->set('flashsession', function () {
            return new FlashSession([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });
    }

    private function setRouter($di)
    {
        $di->set('router', function () {
            return include BASE_DIR . "config/router.php";
        });
    }

    private function setSession($di)
    {
        $di->setShared('session', function () {
            $session = new SessionAdapter();
            $session->start();
        
            return $session;
        });
    }

    private function setHelper($di)
    {
        // helper
        $di->set('helper', function() {
            return new ViewHelper();
        });

        // tag
        $di->set('tagview', function() {
            return new TagHelper();
        });
    }

}
