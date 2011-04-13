<?php

/*
 * Abstract for classes that emulate PDO Statement.
 *
 * @link http://php.net/manual/en/class.pdostatement.php
 */

require_once dirname(__FILE__) . '/genericPDOStatement.interface.php';
abstract class genericPDOStatement implements genericPDOStatementInterface
{

  /**
   *
   * @var string
   */
  protected $queryString;

  /**
   * Returns the property queryString
   * @return string
   */
  public function getQueryString()
  {
    return $this->queryString;
  }

  /**
   * Bind a column to a PHP variable
   * @link http://www.php.net/manual/en/pdostatement.bindcolumn.php
   *
   * @param mixed $column
   * @param mixed $param
   * @param int $type
   * @param int $maxlen
   * @param mixed $driverdata
   * @return bool
   */
  public function bindColumn($column, &$param, int $type = null, int $maxlen = null, $driverdata = null)
  {
    return true;
  }

  /**
   * Binds a parameter to the specified variable name
   * @link http://www.php.net/manual/en/pdostatement.bindparam.php
   *
   * @param mixed $parameter
   * @param mixed $variable
   * @param int $data_type
   * @param int $length
   * @param mixed $driver_options
   * @return bool
   */
  public function bindParam($parameter, &$variable, int $data_type = PDO::PARAM_STR, int $length = null, $driver_options = null)
  {
    return true;
  }

  /**
   * Binds a value to a parameter
   * @link http://www.php.net/manual/en/pdostatement.bindvalue.php
   * 
   * @param mixed $parameter
   * @param mixed $value
   * @param int $data_type
   * @return bool
   */
  public function bindValue($parameter, $value, int $data_type = PDO::PARAM_STR)
  {
    return true;
  }

  /**
   * Closes the cursor, enabling the statement to be executed again.
   * @link http://www.php.net/manual/en/pdostatement.closecursor.php
   *
   * @return boolean
   */
  public function closeCursor()
  {
    return true;
  }

  /**
   * Returns the number of columns in the result set
   * @link http://www.php.net/manual/en/pdostatement.columncount.php
   *
   * @return int
   */
  public function columnCount()
  {
    $count = 0;

    return (int) $count;
  }

  /**
   * Dump an SQL prepared command
   *
   *
   * @return bool
   */
  public function debugDumpParams()
  {
    return true;
  }

  /**
   * Fetch the SQLSTATE associated with the last operation on the statement handle
   *
   * @return string
   */
  public function errorCode()
  {
    return (string) $errorCode;
  }

  /**
   * Fetch extended error information associated with the last operation on the statement handle
   * 
   * @return array
   */
  public function errorInfo()
  {
    $errorInfo = array();

    return $errorInfo;
  }

  /**
   * Executes a prepared statement
   *
   * @param array $input_parameters
   * @return bool
   */
  public function execute(array $input_parameters = null)
  {
    return true;
  }

  /**
   * Fetches the next row from a result set
   *
   * @param int $fetch_style
   * @param int $cursor_orientation
   * @param int $cursor_offset
   */
  public function fetch(int $fetch_style = PDO::FETCH_BOTH, int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    switch ($fetch_style)
    {
      case PDO::FETCH_ASSOC:
        return $this->fetchAssoc($cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_BOTH:
        return $this->fetchBoth($cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_BOUND:
        return $this->fetchBound($cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_CLASS:
        return $this->fetchClass($fetch_style, $cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_INTO:
        return $this->fetchInto($cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_LAZY:
        return $this->fetchLazy($cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_NUM:
        return $this->fetchNum($cursor_orientation, $cursor_offset);
        break;

      case PDO::FETCH_OBJ:
        return $this->fetchObj($cursor_orientation, $cursor_offset);
        break;

      default:

        throw new Exception(__FUNCTION__ . ". Fetch style not suported, see http://www.php.net/manual/en/pdostatement.fetch.php");
        break;
    }
  }

  /**
   * returns an array indexed by column name as returned in your result set
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return array
   */
  protected function fetchAssoc(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return $fetch;
  }

  /**
   * returns an array indexed by both column name and 0-indexed column number as returned in your result set
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return array
   */
  protected function fetchBoth(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    throw new Exception(__FUNCTION__ . ". Deprectaded PDO::FETCH_BOTH fetch style method. Read a good book about code optimization and known why.");

    return $fetch;
  }

  /**
   * returns TRUE and assigns the values of the columns in your
   * result set to the PHP variables to which they were bound
   * with the PDOStatement::bindColumn() method
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return bool
   */
  protected function fetchBound(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return true;
  }

  /**
   * returns a new instance of the requested class, mapping the columns
   * of the result set to named properties in the class. If fetch_style
   * includes PDO::FETCH_CLASSTYPE
   * (e.g. PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE) then the name of the
   * class is determined from a value of the first column.
   *
   * @param int $fetch_style
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return object
   */
  protected function fetchClass(int $fetch_style, int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return $fetch;
  }

  /**
   * updates an existing instance of the requested class,
   * mapping the columns of the result set to named properties
   * in the class
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return array
   */
  protected function fetchInto(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return $fetch;
  }

  /**
   * combines PDO::FETCH_BOTH and PDO::FETCH_OBJ, creating the
   * object variable names as they are accessed
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return object
   */
  protected function fetchLazy(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return $fetch;
  }

  /**
   * returns an array indexed by column number as returned in
   * your result set, starting at column 0
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return array
   */
  protected function fetchNum(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return $fetch;
  }

  /**
   * returns an anonymous object with property names that correspond
   * to the column names returned in your result set
   *
   * @param int $cursor_orientation
   * @param int $cursor_offset
   * @return object
   */
  protected function fetchObj(int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0)
  {
    return $fetch;
  }

  /**
   * Returns an array containing all of the result set rows
   *
   * @param int $fetch_style
   * @param mixed $fetch_argument
   * @param array $ctor_args
   * @return array;
   */
  public function fetchAll(int $fetch_style = PDO::FETCH_BOTH, $fetch_argument = null, array $ctor_args = array())
  {
    switch ($fetch_style)
    {
      case PDO::FETCH_ASSOC:
        return $this->fetchAllAssoc($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_BOTH:
        return $this->fetchAllBoth($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_BOUND:
        return $this->fetchAllBound($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_CLASS:
        return $this->fetchAllClass($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_INTO:
        return $this->fetchAllInto($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_LAZY:
        return $this->fetchAllLazy($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_NUM:
        return $this->fetchAllNum($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_OBJ:
        return $this->fetchAllObj($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_COLUMN:
        return $this->fetchAllColumn($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_COLUMN || PDO::FETCH_UNIQUE:
        return $this->fetchAllColumnUnique($fetch_argument, $ctor_args);
        break;

      case PDO::FETCH_COLUMN || PDO::FETCH_GROUP:
        return $this->fetchAllColumnGroup($fetch_argument, $ctor_args);
        break;

      default:

        throw new Exception(__FUNCTION__ . ". Fetch style not suported, see http://www.php.net/manual/en/pdostatement.fetch.php");
        break;
    }
  }

  protected function fetchAllAssoc($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllBoth($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllBound($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllClass($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllInto($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllLazy($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllNum($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllObj($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllColumn($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllColumnUnique($fetch_argument, $ctor_args)
  {
    
  }

  protected function fetchAllColumnGroup($fetch_argument, $ctor_args)
  {
    
  }

  /**
   * Returns a single column from the next row of a result set
   *
   * @param int $column_number
   * @return string
   */
  public function fetchColumn(int $column_number = 0)
  {
    return $string;
  }

  /**
   * Fetches the next row and returns it as an object.
   * This function is an alternative to PDOStatement::fetch()
   * with PDO::FETCH_CLASS or PDO::FETCH_OBJ
   *
   * @param string $class_name
   * @param array $ctor_args
   * @return mixed
   */
  public function fetchObject(string $class_name = "stdClass", array $ctor_args = array())
  {

  }

  /**
   * Retrieve a statement attribute
   *
   * @param int $attribute
   * @return mixed
   */
  public function getAttribute(int $attribute)
  {
    return $mixed;
  }


  /**
   * Returns metadata for a column in a result set
   *
   * @param int $column
   * @return array
   */
  public function getColumnMeta(int $column)
  {
    return $array;
  }

  /**
   * Advances to the next rowset in a multi-rowset statement handle
   * 
   * @return bool
   */
  public function nextRowset()
  {
    return true;
  }

  /**
   * Returns the number of rows affected by the last SQL statement
   *
   * @return int
   */
  public function rowCount()
  {
    return $int;
  }


  /**
   * Set a statement attribute
   *
   * @param int $attribute
   * @param mixed $value
   * @return bool
   */
  public function setAttribute(int $attribute, mixed $value)
  {
    return true;
  }

  /**
   * s'ha de fer overload, mirar tècniques
   */
  /**
   * setFetchMode must be overloaded
   * @see http://php.net/manual/en/language.oop5.overloading.php
   */
  // bool PDOStatement::setFetchMode ( int $mode )
  // bool PDOStatement::setFetchMode ( int $PDO::FETCH_COLUMN , int $colno )
  // bool PDOStatement::setFetchMode ( int $PDO::FETCH_CLASS , string $classname , array $ctorargs )
  // bool PDOStatement::setFetchMode ( int $PDO::FETCH_INTO , object $object )

}

