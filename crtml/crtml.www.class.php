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
	
	public function __construct()
	{
		$this->html = new crtmlHTML('Transitional');
	}
	
	public function Render()
	{
		$this->html->set_head($this->head);
		$this->html->set_body($this->body);
		
		return $this->html->Render();
		
	}
	
}
?>