<?php

class dbCfg
{
	const HOST = DB_SERV;
	const DB = DB_NAME;
	const USER = DB_USER;
	const PASS = DB_PASS;    
}

class DB
{
  private static $_instance = null;
  
  private function __construct() {}

  public static function getInstance()
  {
    if(!self::$_instance)
    {
        try {
            self::$_instance = new PDO('mysql:host='.dbcfg::HOST.';dbname='.dbCfg::DB, dbCfg::USER, dbCfg::PASS);    
        } catch (PDOException $e) {
            echo "Ошибка соединения с базой. ";
            die;
        }        
           
        //self::$_instance->query('SET NAMES "utf8"');
        self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return self::$_instance;
  }
}
?>
