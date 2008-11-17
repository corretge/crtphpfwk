<?php

/**
 * Recuperem tots els objectes HTML
 */
require_once('crtml.classes.php');

class crtmlMenu
{
	protected $html;
	
	protected $script;
	
	public $head;
	
	public $body;
	
	
	
	public function __construct()
	{
		$this->html = new crtmlDIV(uniqid());
		
		$this->script = "
		function showCrtmlMenuBloc(menuId)
		{
		";
	}
	
	public function __toString()
	{
		$return = (string) $this->html;
		
		
		return $return;
	}
	
	public function renderScript()
	{
		
		$this->script .= "
		document.getElementById(menuId).style.display='block';
		}
		";
		
		return $this->script;
		
	}
	
	public function addContingut($obj)
	{
		$this->html->addContingut($obj);
		
		$this->script .= "document.getElementById('" . $obj->get_uniqueId() ."').style.display='none';
		";
		
		//echo "<pre>";
		//echo $obj->get_uniqueId();
		//var_dump($obj);
	}
}

class crtmlMenuBloc
{
	
	protected $html;
	
	protected $punts;
	
	protected $titol;
	
	protected $uniqueId;
	
	public function __construct($titol)
	{
		
		$this->set_titol($titol);
		$this->set_uniqueId();
		
		/**
		 * Preparem el contenidor del bloc
		 */
		$this->html = new crtmlDIV(uniqid());		
		//$this->html->addEvent('onmouseover' , "document.getElementById('$this->uniqueId').style.display='block'");
		//$this->html->addEvent('onmouseout' , "document.getElementById('$this->uniqueId').style.display='none'");
		$this->html->addEvent('onclick', " showCrtmlMenuBloc('$this->uniqueId')");
		
		/**
		 * @todo Muntar una array i una funció que quan mostri un element amb un click,
		 * 		amagi la resta
		 */
		//$this->html->addEvent('onclick' , "document.getElementById('$this->uniqueId').style.display='block'");
		
		/**
		 * Preparem el contenidor de punts
		 */
		$this->punts = new crtmlDIV($this->uniqueId);
		$this->punts->setStyle('display: none;');
		$this->punts->setClass('crtmlMenuDrop');
	}
	
	/**
	 * Assignem un valor a $titol
	 *
	 * @param string $titol
	 */
	public function set_titol($titol)
	{
			$this->titol = $titol;
	}
	
	/**
	 * Assignem un valor Unique a $uniqueId
	 */
	public function set_uniqueId()
	{
			$this->uniqueId = uniqid('crtmlMenuBloc');
	}
	
	/**
	 * Retornem el valor de $uniqueId
	 *
	 * @return string
	 */
	public function get_uniqueId()
	{
		return $this->uniqueId;
	}
	/**
	 * Afegim un punt de menú
	 *
	 * @param object $crtmlMenuPunt
	 */
	public function addMenuPunt($crtmlMenuPunt)
	{
		$this->punts->addContingut($crtmlMenuPunt);
	}
	
	public function __toString()
	{
		$this->html->addContingut("<h3>$this->titol</h3>");
		$this->html->addContingut($this->punts);
		
		return $this->html->RendeR();
	}
	
}

/**
 * Un punt de menú és idèntic a un enllaç amb la única excepció
 * de que li afegim el <li>
 */
class crtmlMenuPunt extends crtmlA 
{
	
	public function __toString()
	{
		$return = "<li>";
		
		$return .= parent::__toString();
		
		$return .= "</li>";
		
		return $return;
	}
}
?>