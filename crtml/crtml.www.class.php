<?php

/**
 * Recuperem tots els objectes HTML
 * i ens assegurem de que estem recuperant el de la mateixa carpeta.
 */
$jd = dirname(__FILE__);
require_once($jd . '/crtml.classes.php');

class crtmlWWW
{
	protected $html;
	
	public $head;
	
	public $body;

	/**
	 * Indiquem quina és la versió de la classe. 
	 *
	 * @var integer
	 */
	protected $version = 2.0;
	
	protected $CharSet = 'utf-8';
	
	public function __construct()
	{
		$this->html = new crtmlHTML('Transitional');
	}
	

	/**
	 * Retornem la versió de la classe.
	 *
	 * @return integer
	 */
	public function getVersion()
	{
		return $this->version;
	}
	
	/**
	 * Establim el valor a CharSet
	 */
	public function setCharSet($CharSet)
	{
		$this->CharSet = $CharSet;
	}
	
	/**
	 * Retornem el valor de CharSet
	 */
	public function rtvCharSet()
	{
		return $this->CharSet;
	}
	
	public function header()
	{
		header("Content-Type: text/html; charset={$this->CharSet}");
	}
		
	/**
	 * fem la representació escrita de l'objecte
	 *
	 * @return string
	 */
	public function __toString()
	{
		$this->html->set_head($this->head);
		$this->html->set_body($this->body);
		
		return (string) $this->html;
		
	}
	
}
?>