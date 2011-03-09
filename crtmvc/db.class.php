<?php

namespace Corretge\Crtmvc\Model\MySQL;


/*
 * Classe per a la connexió amb la base de dades.
 *
 * Al no disposar del ZendFramework improvitzem aquesta
 */

class db
{

	protected $server;
	protected $username;
	protected $password;
	protected $database;
	public $conn;
	public $rows;
	public $result;
	protected $silentMode = false;
	protected $lastSqlExecutat;
	protected $lastId;
	protected $lastInsertTable;
  

	public function __construct()
	{
		
	}

	/**
	 * ens connectem a la base de dades.
	 */
	public function connect($persistent = false)
	{
		/**
		 * si demanen connexió persistent, ho fem.
		 */
		if ($persistent)
		{
			$this->conn = mysql_pconnect($this->server, $this->username, $this->password);
		}
		else
		{
			//echo "mysql -h {$this->server} -u {$this->username} -p{$this->password}<br>";
			$this->conn = mysql_connect($this->server, $this->username, $this->password);
		}

		if (!$this->conn)
		{
			throw new Exception("*ERR can't connect to DB: " . mysql_error());
		}

		/**
		 * establim UTF8 com a codificació per omissió
		 */
		$this->lastSqlExecutat = 'SET NAMES utf8';
		mysql_query('SET NAMES utf8', $this->conn);


		/**
		 * emprem la base de dades per omissió
		 */
		$this->useDb($this->database);
	}

	/**
	 * tanquem la connexió amb la base de dades.
	 */
	public function disconnect()
	{
		mysql_close($this->conn);
	}

	/**
	 * Executem una consulta
	 *
	 * @param string $sql
	 */
	public function query($sql)
	{
		/**
		 * si el query s'executa satisfactòriament,
		 * creem rows, un iterable.
		 */
		$this->lastSqlExecutat = $sql;
		if ($this->result = mysql_query($sql))
		{

			/**
			 * preguntem si és un recurs, doncs pot ser un Insert
			 * satisfactori que no retorna resultat.
			 */
			if (is_resource($this->result))
			{
				$this->rows = new rows($this->result);
			}
			else
			{
				unset($this->rows);
			}
		}
		else
		{
			unset($this->rows);

			if ($err = mysql_error($this->conn))
			{
				if (!$this->silentMode)
				{
					throw new Exception("*ERR: {$err}");
				}
			}
		}
	}

	/**
	 * Recuperem un registre
	 *
	 * @return array
	 */
	public function fetchRow()
	{
		return mysql_fetch_array($this->result, MYSQL_ASSOC);
	}

	/**
	 * Retornem el primer camp de la primera columna del resultat d'una sentència
	 * 
	 * @param string $sql
	 * @return string
	 */
	public function getOne($sql)
	{
		$this->query($sql);
		return current($this->fetchRow());

	}

	/**
	 * Establim les credencials
	 *
	 * @param string $server  host
	 * @param string $user user
	 * @param string $password password
	 * @param boolean $base64  password base64 encoded
	 */
	public function setCredentials($server, $user, $password, $base64 = false)
	{
		$this->server = $server;
		$this->username = $user;
		if ($base64)
		{
			$this->password = base64_decode($password);
		}
		else
		{
			$this->password = $password;
		}
	}

	public function useDb($db)
	{

		mysql_select_db($db, $this->conn);
		$this->database = $db;
	}

	/**
	 * Indiquem si volem continui encara que hi hagi errors o que salti.
	 *
	 * @param bool $mode
	 */
	public function setSilentMode($mode)
	{
		$this->silentMode = ($mode === true);
	}

	/**
	 * Creem un registre
	 * @param string $taula
	 * @param array $valors Array associativa amb el nom de camp i valors
	 */
	public function insertInto($taula, $valors, $delayed = false)
	{
		$sql = "insert ";

		if ($delayed)
		{
			$sql .= "delayed ";
		}

		$sql .= "into {$taula} (";

		$sql .= implode(', ', array_keys($valors));

		$sql .= ") VALUES(";

		/**
		 * preprocessem els valors
		 */
		foreach ($valors as $key => $val)
		{
			if (is_string($val))
			{
				$valors[$key] = "'" . mysql_real_escape_string($val) . "'";
			}
			elseif (is_null($val))
			{
				$valors[$key] = "null";
			}
		}

		/**
		 * i els afegim a la sentència
		 */
		$sql .= implode(',', $valors);

		$sql .= ")";

		$this->lastSqlExecutat = $sql;
		mysql_query($sql);

		if ($err = mysql_error($this->conn))
		{
			if (!$this->silentMode)
			{
				throw new Exception("*ERR: {$err} <hr> {$this->lastSqlExecutat} <hr>");
			}
		}

		/**
		 * si la inserció ha anat bé, recuperem
		 * el darrer uniqueId autonumèric (zero si no hi ha clau)
		 * i guardem la darrera taula a la que es correspon aquest id.
		 */
		$this->lastId = mysql_insert_id();
		$this->lastInsertTable = $taula;
	}

	/**
	 * Actualitzem un registre
	 * @param string $taula
	 * @param array $valors Array associativa amb el nom de camp i valors
	 * @param array $clau Array amb la clau complerta
	 */
	public function update($taula, $valors, $clau = null)
	{
		$sql = "update ";


		$sql .= " {$taula} SET ";

		/**
		 * carreguem els valors
		 */
		$sep = "";
		foreach ($valors as $key => $val)
		{
			$sql .= $sep;
			if ($val instanceof dbSentence)
			{
				$sql .= " {$key} = {$val}";
			}
			elseif (is_string($val))
			{
				$sql .= " {$key} = '" . mysql_real_escape_string($val) . "'";
			}
			elseif (is_null($val))
			{
				$sql .= " {$key} = null";
			}
			else
			{
				$sql .= " {$key} = " . mysql_real_escape_string($val);
			}
			$sep = ", ";
		}

		if (isset($clau) and is_array($clau))
		{
			$sql .= " WHERE ";
			$sep = "";
			foreach ($clau as $key => $val)
			{
				$sql .= $sep;
				if (is_string($val))
				{
					$sql .= " {$key} = '" . mysql_real_escape_string($val) . "'";
				}
				elseif (is_null($val))
				{
					$sql .= " isnull({$key}) ";
				}
				else
				{
					$sql .= " {$key} = " . mysql_real_escape_string($val);
				}
				$sep = " AND ";
			}
		}

		$this->lastSqlExecutat = $sql;
		mysql_query($sql);

		if ($err = mysql_error($this->conn))
		{
			if (!$this->silentMode)
			{
				throw new Exception("*ERR: {$err}");
			}
		}
	}

	public function getLastSqlExecutat()
	{
		return $this->lastSqlExecutat;
	}

	public function getLastId()
	{
		return $this->lastId;
	}

}

/**
 * Processem els registres d'una consulta
 */
class rows implements Iterator
{

	private $pos = 0;
	private $result;
	private $row;
	private $valid;
	private $numRows;

	public function __construct($result)
	{
		$this->result = $result;
		$this->pos = 0;
		$this->numRows = mysql_num_rows($result);
	}

	public function rewind()
	{
		$this->pos = 0;
		if ($this->numRows > 0)
		{
			$this->valid = mysql_data_seek($this->result, $this->pos);
		}
	}

	public function current()
	{
		$this->row = mysql_fetch_assoc($this->result);
		return $this->row;
	}

	public function key()
	{
		return $this->pos;
	}

	public function next()
	{
		++$this->pos;
		if ($this->pos >= $this->numRows)
		{
			$this->valid = false;
		}
		else
		{
			$this->valid = mysql_data_seek($this->result, $this->pos);
		}
	}

	function valid()
	{
		return $this->valid;
	}

	public function getNumRows()
	{
		return $this->numRows;
	}

	public function getRow()
	{
		return $this->row;
	}

}

/**
 * Classe per a especificar que el contingut d'un camp és una setència i 
 * no una cadena.
 */
class dbSentence
{

	/**
	 *
	 * @var string Sentencia SQL o funció
	 */
	protected $stmt;

	/**
	 * Construim un dbSentence
	 * @param string $stmt Sentencia SQL o funció
	 */
	public function __construct($stmt)
	{
		$this->setSentence($stmt);
	}

	/**
	 * Establim la sentència.
	 *
	 * @param string $stmt Sentencia SQL o funcio
	 */
	public function setSentence($stmt)
	{
		$this->stmt = $stmt;
	}

	/**
	 * Retornem la sentència
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->stmt;
	}

}

?>
