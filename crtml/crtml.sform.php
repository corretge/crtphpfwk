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
	public function Render()
	{
		/**
		 * interceptem el contingut del Fieldset i el
		 * convertim en una taula.
		 * Ho fem en dues pases per a debug purpose.
		 */
		$table = $this->renderFldsetAsTable($this->fldset);
			
		$this->add2Form($table);
		$this->add2Form($this->butonera);
		return $this->form->Render();
	}
	
	public function renderFldsetAsTable($fldset)
	{
		
		$newFldset = new crtmlFIELDSET($fldset->getLegend());
		$tdL = Array();
		$tdF = Array();
		$tdN = 0;
		
		$table = "<table>";
		
		$Continguts = $fldset->getContinguts();
		/**
		 * Cerquem els label
		 */
		foreach ($Continguts as $Contingut) 
		{
			/**
			 * afegim 1 al comptador de línies.
			 */
			$tdN ++;

			/**
			 * només incorporarem el que estigui definit com a
			 * label
			 */
			
			if (is_object($Contingut) and get_class($Contingut) == 'crtmlLABEL')
			{
				$tdL[$tdN] = (string) $Contingut->getLabel();
				$tdF[$tdN] = "";
				
				/**
				 * Ara incorporem només els objectes que estiguin
				 * dins el Label, així evitem duplicar l'etiqueta
				 */
				$lCon = $Contingut->getContinguts();
				foreach ($lCon as $Camp) 
				{
					if (is_object($Camp))
					{
						$tdF[$tdN] .= " " . (string) $Camp->Render();		
					}
				}
			}
			
		}
		
		/**
		 * muntem la taula pròpiament dita
		 */
		
		
		for ($n=1; $n <= $tdN; $n++)
		{
			$table .= "<tr>\n";
			$table .= "<td>" . $tdL[$n] . "</td>\n";
			$table .= "<td>" . $tdF[$n] . "</td>\n";
			$table .= "</tr>\n";
		}
		
		$table .= "</table>";
		
		$newFldset->addContingut((string) $table);
		
		return $newFldset;
	}
}

?>