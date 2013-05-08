<?php

class fDB extends DB {
//Расширенный класс DB
  protected static $_connected = false;

  public static function getInstance(){
  //выставляем кодовые страницы при коннекте
  if (!self::$_connected) {
      $res = parent::getInstance();
      $res->query("set names 'utf8'");
      //$res->query("set client_encoding='win1251'");
      return $res;
      }
  }

  protected static function _prep_param($param) {   
  if (is_array($param)) {
    foreach ($param as $key => $value){
      $param[$key] = @iconv ( 'UTF-8//IGNORE', 'UTF-8//IGNORE',$value);
      }

    }
  
  return $param;
  }
  protected static function _prep_sql($sql,$param) {
      global $sql_debug;
      if (isset($param[':table'])) {
          $sql = str_replace(':table',$param[':table'],$sql);
      }
      $sql_debug .= $sql.'<br>';
      return $sql;
  }

  protected static function _prep_query($sql,$param) {
  //подготавливаем sql выполняем и возвращаем результат
  $db = self::getInstance();
  $sql = self::_prep_sql($sql,$param);
  $st = $db->prepare($sql);
  
  
  if ( isset($param[':table']) )
    unset($param[':table']);
  
  if (is_array($param)) {
    $param = self::_prep_param($param); 
       
    foreach ($param as $key => $value) {
         $st->bindValue($key, $param[$key]);
    }
  }
  $st->execute();
  return $st;
  }
  
   protected static function _prep_query2($sql,$param) {
  //подготавливаем sql выполняем и возвращаем результат
  $db = self::getInstance();
  $sql = self::_prep_sql($sql,$param);
  $st = $db->prepare($sql);
  if (is_array($param)) {
    //$param = self::_prep_param($param); 
       
    foreach ($param as $key => $value) {
         $st->bindValue($key, $param[$key]);
    }
  }
  $st->execute();
  return $st;
  }

  protected static function _prep_exec($sql,$param) {
  //подготавливаем sql выполняем и возвращаем результат
  $st = self::_prep_query($sql,$param);
  $res = $st->rowCount();
  return $res;
  }
  
  public static function fexec2($sql,$param = Null) {    
  $st = self::_prep_query2($sql,$param);
  $res = $st->rowCount();
  return $res;
  }
  

  //Проверка на существование таблицы в базе
  //mag
  public static function checkForTable($tableName){
    
    $db = self::getInstance();
    $q = $db->prepare("
      SELECT COUNT(*) as cnt FROM pg_tables WHERE
	tablename= :table
    ");
    $q->bindParam(':table', $tableName, PDO::PARAM_STR);
    $q->execute();
    $res = $q->fetch(PDO::FETCH_ASSOC);

    if ( $res['cnt'] > 0 )
      return true;

    return false;
  }
  
  public static function fquery($sql,$param = Null) {
  //возвращает асоциотивный массив из запроса
  $st = self::_prep_query($sql,$param);
  $res = $st->fetch(PDO::FETCH_ASSOC);
  return $res;
  }

  public static function fqueryAll($sql,$param = Null) {
  //возвращает асоциотивный массив из запроса
  $st = self::_prep_query($sql,$param);
  $res = $st->fetchAll(PDO::FETCH_ASSOC);
  return $res;
  }

  public static function fexec($sql,$param = Null) {
  //возвращает кол-во обработанных строк
  $res = self::_prep_exec($sql,$param);
  return $res;
  }

  public static function fscalar($sql,$param = Null) {
  //возвращает первое значение первой строки из результата запроса
  $st = self::_prep_query($sql,$param);
  $res = $st->fetch(PDO::FETCH_COLUMN,0);
  if ($res)
    return $res;
    else return null;
  }
  
  public static function lastID($s = '') {
    return parent::getInstance()->lastInsertId($s);
  }

}
