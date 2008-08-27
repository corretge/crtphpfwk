<?php
/**
 * creació d'un formulari senzill
 */

/**
 * recuperem les classes crtml
 */
$dir = dirname(__FILE__);
require_once $dir . '/crtml.classes.php';

class sform
{
	protected $id;
	protected $form;
	protected $fldset;
	protected $butonera;
	
	public function __construct($titol, $id = 'UNIQUEID')
	{
		if ($id = 'UNIQUEID')
		{
			$this->id = uniqid('crtml');
		}
		else
		{
			$this->id = $id;
		}
		
		$this->form = new crtmlFORM('');
		$this->form->setId($id);
		
		
		$this->fldset = new crtmlFIELDSET(new crtmlLEGEND($titol));
		
		$this->butonera = new crtmlDIV('butonera','','butonera');
		
	}
	
	public function addInput($label, $field, $type, $value)
	{
		$lb = new crtmlLABEL($label);
		$fld = new crtmlINPUT($field);
		$fld->setValue($value);
		$fld->setType($type);
		$lb->addContingut($fld);
		$this->fldset->addContingut($lb);
	}
	
	public function addSimpleButton($label, $action)
	{
		$btn = new crtmlINPUT($label);
		$btn->setType('Submit');
		$this->butonera->addContingut($btn);
	}

	public function addButton($button)
	{
		$this->butonera->addContingut($button);
	}	
	
	public function Render()
	{
		$this->form->addContingut($this->fldset);
		$this->form->addContingut($this->butonera);
		return $this->form->Render();
	}
	
	public function renderScript()
	{
		return "";	
	}
	
}
?>