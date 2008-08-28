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
	
	public function __construct($titol, $id)
	{
		if ($id == 'UNIQUEID')
		{
			$this->id = uniqid('crtml');
		}
		else
		{
			$this->id = $id;
		}
		
		$this->form = new crtmlFORM('', 'POST');
		$this->form->setId($this->id);
		
		
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
	
	public function add2Form($object)
	{
		$this->form->addContingut($object);
	}
	
	public function add2Fieldset($object)
	{
		$this->fldset->addContingut($object);
	}
	
	public function Render()
	{
		$this->add2Form($this->fldset);
		$this->add2Form($this->butonera);
		return $this->form->Render();
	}
	
	public function renderScript()
	{
		return "";	
	}
	
}


/**
 * classe que crearà el formulari dins una taula amb
 * les etiquetes a una banda i els camps a un altra.
 */
class sFormTable extends sform
{
	
}

?>