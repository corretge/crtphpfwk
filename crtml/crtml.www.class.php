<?php

/**
 * Recuperem tots els objectes HTML
 */
require_once('crtml.classes.php');

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