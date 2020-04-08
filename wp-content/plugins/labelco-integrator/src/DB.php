<?php
namespace Labelco;

use \PDO;

class DB
{
    protected static $instance;
    protected $pdo;

    //private $con = false;


    protected function __construct(){
        $opt = array(

            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' ",
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        $options = get_option('LabelcoOptionName');//get option table with  mssql db connect

        if ( !empty($options) && !empty($options["db_name"])
                && !empty($options["host_name_port_no"]) && !empty ($options["charset_code"])
                && !empty($options["user_login"]) && !empty($options["user_pass"]) ) {

                $dsn = 'dbname = ' . trim($options["db_name"]) . ';host=' . trim($options["host_name_port_no"]) . ';charset='
                    . trim($options["charset_code"]);
                $this->pdo = new PDO('dblib:' . $dsn, trim($options["user_login"]),
                                trim($options["user_pass"]), $opt);
                }//if empty option

    }//constructor end


    // a classical static method to make it universally available
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // a proxy to native PDO methods,invoke when methods is out of object range but used within object
    public function __call($method, $args)
    {    //invoke function with param as array elements
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    // a helper function to run prepared statements smoothly
    public function run($sql, $args = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

}