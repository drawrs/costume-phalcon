<?php

class Artisan
{

    private $config;
    private $lenght;
    private $result;

    public function __construct(array $config, int $lenght)
    {
        $this->config = $config;
        $this->lenght = $lenght;
    }

    private function commands()
    {
        $file = realpath(dirname(__FILE__) . "/../qodr_help");
        return file_get_contents($file);
    }

    private function check()
    {
        if ($this->lenght > 1) {
            $result = $this->execute();
        } else {
            $result = $this->commands();
        }

        return $result;
    }

    private function execute()
    {
        switch ($this->config[1]) {
            case 'phinx':
                $result = $this->phinx();
                break;
            case 'controller':
                $result = $this->controller();
                break;
            case 'model':
                $result = $this->model();
                break;
            case 'core':
                $result = $this->core();
                break;
            case 'vuko:fix':
                $result = $this->vukoFix();
                break;
            default:
                $result = "Error commands not found";
                break;
        }
        return $result;
    }

    public function run()
    {
        return $this->check();
    }

    public function phinx()
    {
        if ($this->lenght > 2) {
            $commandss = implode(' ', $this->config);
            $commands  = substr($commandss, 11);
            $path      = realpath(dirname(__FILE__) . "/../../bin/phinx");
            return system($path . ' ' . $commands);
        } else {
            return system(realpath(dirname(__FILE__) . "/../../bin/phinx"));
        }
    }

    public function controller()
    {
        if (!empty($this->config[2])) {
            $folder = (!empty($this->config[3])) ? '/' . $this->config[3] : '' ;
            $nmfolder = ($folder !== '') ? '\\' . $this->config[3] : '' ;
            $path  = realpath(dirname(__FILE__) . "/../../app/controllers/") . $folder;
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
            $path .= '/' . $this->changeName($this->config[2]) . 'Controller.php';
            $file = file_get_contents(realpath(dirname(__FILE__) . "/../example/controller"));
            $myfile = fopen($path, "w") or die("Unable to open file!");
            $txt = str_replace('NAME_CONTROLLER', $this->changeName($this->config[2]) . 'Controller', $file);
            $txt = str_replace('\NAME_FOLDER', $nmfolder, $txt);
            fwrite($myfile, $txt);
            if (fclose($myfile)) {
                $result = "Success Create Controller !";
            } else {
                $result = "Error Create Controller !";
            }
            return $result;
        } else {
            return "Commands Failed\nphp qodr controller [name]";
        }
    }

    private function changeName($name)
    {
        $result = '';
        $array  = explode('_', $name);
        foreach ($array as $key => $value) {
            $result .= ucfirst($value);
        }
        return $result;
    }

    public function model()
    {
        if (!empty($this->config[2])) {
            $db   = $this->connetDb();
            $data = $db->query("DESC " . $this->config[2], PDO::FETCH_ASSOC);
            if (empty($data)) {
                die('table not found !' . PHP_EOL);
            } else {
                $table    = $this->config[2];
                $columns  = '';
                while ($row = $data->fetch()) {
                    $columns .= '    public $' . $row['Field'] . ";\n";
                }

                $table_name = $this->changeName($table);
                $table_name = (substr($table_name, -1) == 's') ? $table_name : $table_name . 's' ;

                $path  = realpath(dirname(__FILE__) . "/../../models/");
                $path .= '/' . $table_name . '.php';
                $file = file_get_contents(realpath(dirname(__FILE__) . "/../example/model"));
                $myfile = fopen($path, "w") or die("Unable to open file!");
                $txt = str_replace('NAME_MODEL', $table_name, $file);
                $txt = str_replace('NAME_COLUMNS', $columns, $txt);
                $txt = str_replace('NAME_TABLE', $table, $txt);
                fwrite($myfile, $txt);
                if (fclose($myfile)) {
                    $result = "Success Create Model !";
                } else {
                    $result = "Error Create Model !";
                }
                return $result;
            }
        } else {
            return "Commands Failed\nphp qodr model [name]";
        }
    }

    public function core()
    {
        if (!empty($this->config[2])) {
            $path  = realpath(dirname(__FILE__) . "/../../app/library/Core/");
            $path .= '/' . $this->changeName($this->config[2]) . '.php';
            $file = file_get_contents(realpath(dirname(__FILE__) . "/../example/core"));
            $myfile = fopen($path, "w") or die("Unable to open file!");
            $txt = str_replace('NAME_CORE', $this->changeName($this->config[2]), $file);
            fwrite($myfile, $txt);
            if (fclose($myfile)) {
                $result = "Success Create Core !";
            } else {
                $result = "Error Create Core !";
            }
            return $result;
        } else {
            return "Commands Failed\nphp qodr core [name]";
        }
    }

    public function connetDb()
    {
        $dbhost = getenv('DATABASE_HOST');
        $dbname = getenv('DATABASE_NAME');
        $dbuser = getenv('DATABASE_USERNAME');
        $dbpass = getenv('DATABASE_PASSWORD');

        return new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    }

    public function vukoFix()
    {
        $path = realpath(dirname(__FILE__) . "/../../vendor/voku/portable-utf8/bootstrap.php");
        $file = file_get_contents($path);
        $myfile = fopen($path, "w") or die("Unable to open file!");
        $txt = str_replace('Bootup::filterRequestInputs();', '//Bootup::filterRequestInputs();', $file);
        fwrite($myfile, $txt);
        if (fclose($myfile)) {
            $result = "Success fix vuko !";
        } else {
            $result = "Error fix vuko !";
        }
        return $result;
    }

}