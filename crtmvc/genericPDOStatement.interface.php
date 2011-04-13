<?php

/*
 * Interface to deploy a PDO statement object
 * @link http://php.net/manual/en/class.pdostatement.php
 */

interface genericPDOStatementInterface
{
  /**
   *
   * @var string
   */
  protected $queryString;

  public function getQueryString();

  public function bindColumn ( $column, &$param, int $type = null, int $maxlen = null, $driverdata = null);

  public function bindParam ($parameter, &$variable, int $data_type = PDO::PARAM_STR, int $length = null, $driver_options = null );

  public function bindValue ( $parameter, $value, int $data_type = PDO::PARAM_STR );

  public function closeCursor ();

  public function columnCount ();

  public function debugDumpParams ();

  public function errorCode ( );

  public function errorInfo ( );

  public function execute ( array $input_parameters = null );

  public function fetch ( int $fetch_style = PDO::FETCH_BOTH, int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0 );

  public function fetchAll ( int $fetch_style = PDO::FETCH_BOTH,  $fetch_argument = null, array $ctor_args = array() );

  public function fetchColumn ( int $column_number = 0 );

  public function fetchObject ( string $class_name = "stdClass", array $ctor_args = array());

  public function getAttribute ( int $attribute );

  public function getColumnMeta ( int $column );

  public function nextRowset (  );

  public function rowCount ( );

  public function setAttribute ( int $attribute, mixed $value );

  /**
   * setFetchMode must be overloaded
   * @see http://php.net/manual/en/language.oop5.overloading.php
   */
  //public function setFetchMode ( int $mode );
  // bool PDOStatement::setFetchMode ( int $PDO::FETCH_COLUMN , int $colno )
  // bool PDOStatement::setFetchMode ( int $PDO::FETCH_CLASS , string $classname , array $ctorargs )
  // bool PDOStatement::setFetchMode ( int $PDO::FETCH_INTO , object $object )

}