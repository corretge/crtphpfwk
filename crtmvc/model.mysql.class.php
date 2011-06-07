<?php

/**
 * Breu descripció de l'script
 *
 * @author alex@corretge.cat
 * @link http://corretge.cat
 */

namespace Corretge\Crtmvc;

require_once dirname(__FILE__) . '/db.class.php';

class ModelMysql
{

  /**
   * Object to manage the connection
   * @var db
   */
  protected $db;
  /**
   * Principal database
   * @var string
   */
  protected $database;
  /**
   * Principal Table
   * @var string
   */
  protected $table;
  /**
   * Header nexus with detail tables
   *    [detailTable] =>
   *        [headerFields] =>
   *            [0] => fieldA
   *            [1] => 'value'
   *        [detailFields] =>
   *            [0] => fieldA
   *            [1] => fieldB
   *        [extraWhere] => 'string'
   * @var array
   */
  protected $headerNexus;
  /**
   * Result recordset for the primary table
   * @var array
   */
  public $header;
  /**
   * Result recordsets for the details tables by PK
   * @var arrays
   */
  public $detail;
  /**
   * App configuration in Zend Db format
   * @var ZendIni
   */
  protected $iniConfig;
  /**
   * Cache for primary keys
   * 
   * @var array
   */
  protected $cachePK;
  /**
   * Header's Primary Key
   * @var string
   */
  protected $primaryKey;
  protected $iniFile = null;
  protected $ZfDbSection = null;

  /**
   *
   *
   *
   * @param string $iniFile  Absolute path for ini file
   * @param string $ZfDbSection Database connection section ZendFramework compilant
   * @param string $table
   * @param string database
   */
  public function connect($iniFile = null, $ZfDbSection = null, $table = null, $database = null)
  {
    if (isset($this->db))
    {
      return false;
    }

    require_once('Zend/Config/Ini.php');

    if (empty($iniFile))
    {
      $iniFile = $this->iniFile;
    }
    else
    {
      $this->iniFile = $iniFile;
    }

    if (empty($ZfDbSection))
    {
      $ZfDbSection = $this->ZfDbSection;
    }
    else
    {
      $this->ZfDbSection = $ZfDbSection;
    }

    $this->iniConfig = new \Zend_Config_Ini($iniFile, $ZfDbSection);

    $this->db = new Model\MySQL\db();
    $this->db->setCredentials($this->iniConfig->database->params->host,
            $this->iniConfig->database->params->username,
            $this->iniConfig->database->params->password);
    $this->db->connect();

    $this->db->query("SET NAMES {$this->iniConfig->database->params->charset}");

    /**
     * Si hi ha la taula, li posem, si no, el nom de la classe
     */
    if (!empty($table))
    {
      $this->table = $table;
    }
    elseif(empty($this->table))
    {
      $this->table = \basename(\str_replace('\\', '/', \strtolower(\get_class($this))));
      $jPos = \strrpos($this->table, '_');
      if ($jPos > 0)
      {
        $this->table = \substr($this->table, $jPos+1);
      }
    }
    /**
     * Si hi ha base de dades, li posem, ni no, la de la configuració
     */
    if (isset($database))
    {
      $this->database = $database;
    }
    else
    {
      $this->database = $this->iniConfig->database->params->dbname;
    }


    /**
     * emprem la base de dades per omissió
     */
    $this->db->useDb($this->database);

    /**
     * Establim les relacions de la taula
     */
    $this->initialRelations();

    return true;
  }

  /**
   * Discover database relations header / detail
   */
  public function discoverRelations()
  {

  }

  protected function initialRelations()
  {
    $this->headerNexus = array();
  }

  protected function getPrimaryKey($table = null)
  {
    if (empty($table))
    {
      if (empty($this->primaryKey))
      {
        $this->primaryKey = $this->db->getOne("SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
where TABLE_SCHEMA = '{$this->database}' and TABLE_NAME = '{$this->table}'
and CONSTRAINT_NAME = 'PRIMARY'");
      }

      return $this->primaryKey;
    }
    else
    {
      return $this->db->getOne("SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
where TABLE_SCHEMA = '{$this->database}' and TABLE_NAME = '{$table}'
and CONSTRAINT_NAME = 'PRIMARY'");
    }
  }

  public function getByPK($pkVal)
  {
    if (\is_string($pkVal))
    {
      $where = $this->getPrimaryKey() . " = '" . \mysql_escape_string($pkVal) . "'";
    }
    else
    {
      $where = $this->getPrimaryKey() . " = " . \mysql_escape_string($pkVal);
    }

    return $this->select($where);
  }

  public function select($where, $select = '*', $orderby = null, $limit = null)
  {
    $sql = "SELECT {$select} FROM {$this->database}.{$this->table}
    WHERE {$where} ";

    if (!empty($orderby))
    {
      $sql .= " ORDER BY {$orderby} ";
    }

    if (!empty($limit))
    {
      $sql .= " LIMIT {$limit} ";
    }

    try
    {
      return $this->db->query($sql);
    }
    catch (Exception $e)
    {
      $this->ThrowException($e, $sql);
    }
  }

  public function update()
  {
    
  }

  public function delete()
  {

  }

  public function fetchPairs($sql)
  {
    $ret = array();
    $rows = $this->db->query($sql);

    foreach ($rows as $row)
    {
      $cnt = 0;
      foreach ($row as $field => $val)
      {
        if ($cnt==0)
        {
          $key = $val;

        }
        else
        {
          $ret[$key] = $val;
        }
        $cnt++;
      }
    }

    return $ret;
  }

  protected function ThrowException($e, $explain)
  {
    throw new \Exception($e->getMessage() .
            ' in ' . $e->getFile() .
            ' at line ' . $e->getLine() .
            ' explain: ' . $explain);
  }
}

class ModelMySqlException extends \Exception {}