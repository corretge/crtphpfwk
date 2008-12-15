<?php
/**
 * Conjunt de classes que defineixen els objectes HTML que es fan servir quan es pinta una plana.
 * 
 * Les propietats dels objectes seran els atributs de l'entitat HTML a renderitzar.
 * Més informació:
 * @link http://www.w3.org/TR/html401/#minitoc
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */

/**
 * preparem una excepció crtml
 */
class crtmlException extends Exception { }

/**
 * Definim una classe per als elements HTML.
 * 
 * Contindrà mètodes i propietats comunes a tots els elements HTML.
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
abstract class crtmlBODYelement
{
	
	/**
	 * Petita descripció, títol
	 *
	 * @var string
	 */
	protected $Title;

	/**
	 * Identifiquem la class CSS a emprar
	 *
	 * @var string
	 */
	protected $Class;
	
	/**
	 * Indiquem els events que s'han d'executar.
	 *
	 * Els events vàlids són:
	 * onclick, ondblclick, onmousedown, onmouseup, onmouseover, onmousemove, onmouseout, onkeypress, onkeydown, onkeyup
	 * Algunes entitats tenen més events possibles que s'afegeixen en el constructor.
	 * 
	 * @var Array
	 */
	protected $Events = Array(	'onclick' => "",
								'ondblclick' => "",
								'onmousedown' => "",
								'onmouseup' => "",
								'onmouseover' => "",
								'onmousemove' => "",
								'onmouseout' => "",
								'onkeypress' => "",
								'onkeydown' => "",
								'onkeyup' => "");
	
	/**
	 * Identifiquem l'ID de l'entitat
	 *
	 * @var string
	 */
	protected $Id;
	
	protected $Name;
	
	/**
	 * Establim estil CSS
	 *
	 * @var string
	 */
	protected $Style;
	
	
	/**
	 * Establim Title
	 *
	 * @param string $Title
	 * @see $Title
	 */
	public function setTitle($Title)
	{
		/**
		 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 * $this->Title = htmlentities($Title, ENT_COMPAT);
		 */		
		$this->Title = $Title;
	}

	/**
	 * Establim Class
	 *
	 * @param string $Class
	 * @see $Class
	 */
	public function setClass($Class)
	{
		$this->Class = $Class;
	}

	/**
	 * Establim Id
	 *
	 * @param string $Id
	 * @see $Id
	 */
	public function setId($Id)
	{
		$this->Id = $Id;
	}
	
	/**
	 * Retornem el valor de $Id
	 *
	 * @return string
	 */
	public function get_Id()
	{
		return $this->Id;
	}
	
	/**
	 * Establim Name
	 *
	 * @param string $Name
	 * @see $Name
	 */
	public function setName($Name)
	{
		$this->Name = $Name;
	}
	
	/**	
	 * Establim spanId
	 *
	 * @param string $spanId
	 * @see $spanId
	 */
	public function setspanId($spanId)
	{
		$this->spanId = $spanId;
	}
	
		
	
	/**
	 * Establim Style
	 *
	 * @param string $Style
	 * @see $Style
	 */
	public function setStyle($Style)
	{
		$this->Style = $Style;
	}
	
	/**
	 * Afegim un event al element  onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup 
	 *
	 * @param string $event
	 * @param string $action
	 * @see $Events
	 */
	public function addEvent($event, $accio)
	{
		if (isset($this->Events[strtolower($event)]))
		{
				$this->Events[$event] = $accio;
		}
		else 
		{
			throw new crtmlException("Event <b>$event</b> not found.");
		}
		
	}
	

	/**
	 * Renderitzem el text
	 *
	 * @return string
	 */
	public function __toString()
	{
		/**
		 * La inicialització del tipus d'element la faran les
		 * classes filles.
		 */
		$return = "";
		
		/**
		 * Si han indicat una classe
		 */
		if (isset($this->Class))
		{
			$return .= " class=\"$this->Class\"";
		}
		
		/**
		 * Si han indicat Id
		 */
		if (isset($this->Id))
		{
			$return .= " Id=\"$this->Id\"";
		}
		

		/**
		 * Si han indicat Name
		 */
		if (isset($this->Name))
		{
			$return .= " Name=\"$this->Name\"";
		}
				
		
		/**
		 * Si han indicat un Títol
		 */
		if (isset($this->Title))
		{
			$return .= " title=\"$this->Title\"";
		}		
		
		/**
		 * Si han indicat Style
		 */
		if (isset($this->Style))
		{
			$return .= " Style=\"$this->Style\"";
		}
		
		/**
		 * Si han indicat Events
		 */
		if (isset($this->Events))
		{
			foreach ($this->Events as $Event => $Accio) 
			{
				if ($Accio != "")
				{
					$return .= " $Event=\"$Accio\"";
				}
			}
		}
		
		return $return;
	}
	
	
}

/**
 * Definim un tipus d'element que és un contenidor d'objectes
 */
abstract class crtmlBODYcontainer extends crtmlBODYelement 
{
	/**
	 * Si es declara aquesta array associativa, apliquem restriccions
	 * sobre els tipus d'objecte que es poden attachar
	 *
	 * @var Array
	 */
	protected $restrictConts = false;
	
	/**
	 * Continguts, generalment objectes HTML
	 * 
	 * el fem públic pq es pugui manegar desde 
	 * les aplicacions per l'arbre.
	 *
	 * @var Array
	 */
	public $Continguts = Array();
	
	/**
	 * retornem l'array dels continguts
	 */
	public function getContinguts()
	{
		return $this->Continguts;
	}
	
	/**
	 * Afegim un contingut, objecte crtml
	 *
	 * @param object $Contingut
	 * @param string $pos
	 */
	public function addContingut($Contingut, $pos=null)
	{
		/**
		 * si hi ha cap restricció i no es compleix,
		 * saltem.
		 * si existeix sempre serà una array associativa
		 * amb el nom dels objectes vàlids.
		 */
		if ($this->restrictConts)
		{
			$tipus = get_class($Contingut);
			if (!($this->restrictConts['tipus'] == true))
			{
				$orig = get_class($this);
				throw new crtmlException("$orig Container can't addContingut objects type <b>$tipus</b>.");
			}
			
		}
		
		if (isset($pos))
		{
			$this->Continguts[$pos] = $Contingut;
		}
		else 
		{
			/**
			 * si te ID, coloquem el contingut en aquella ID.
			 */
			if (method_exists($Contingut, 'get_Id'))
			{
				$id = $Contingut->get_Id();
				if ($id != '')
				{
					$this->Continguts[$id] = $Contingut;
				}
				else
				{
					$this->Continguts[] = $Contingut;
				}			
			}
			else
			{
				$this->Continguts[] = $Contingut;
			}
		}
	}
	
	
	public function insereixContinguts()
	{
		$return = "";
		/**
		 * Renderitzem els continguts.
		 * 
		 * Si es tracta d'un objecte el renderitzem i si es
		 * tracta d'una cadena l'afegim tal qual.
		 */
		foreach ($this->Continguts as $Contingut) 
		{
			$return .= (string) $Contingut;
		}
		
		return $return;
	}
	
}

/**
 * Definim l'element Paràgraf HTML
 *
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 2.0
 * @package crtml
 */
class crtmlP extends crtmlBODYcontainer 
{
	
	/**
	 * Indiquem l'aliniació del paràgref respecte el text
	 * valors vàlids:
	 * left|center|right|justify 
	 * 
	 * <b>Atenció:</b>
	 * Intentarem no emprar aquesta propietat i fer servir estils CSS.
	 *
	 * @var string
	 * @link http://www.w3.org/TR/html401/present/graphics.html#adef-align
	 */
	protected $Align;

	
	
	
	
	/**
	 * Constructor de P.
	 *
	 * @param string $text
	 * @param string $classe
	 */
	public function __construct($text = null, $classe=null) 
	{
		$this->setText($text);
		$this->setClass($classe);
	}
	
	/**
	 * Establim Text
	 *
	 * @param string $Text
	 * @see $Text
	 */
	public function setText($Text)
	{
	
	/**
   	 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 * $this->Text = htmlentities($Text, ENT_COMPAT);
		 */
		$this->addContingut($Text);
	}

	
	/**
	 * Establim Align left|center|right|justify 
	 *
	 * @param string $Align
	 * @see $Align
	 */
	public function setAlign($Align)
	{
		switch (strtolower($Align))
			{
				case 'left':
				case 'center':
				case 'right':
				case 'left':
				case 'justify':
					$this->Align = $Align;
					break;
				/**
				 * Si no és cap dels events permesos, donem un error fatal.
				 */
				default:
					throw new crtmlException("Align <b>$Align</b> not found.");
					break;
			}		

	}

			
	/**
	 * Renderitzem el text
	 *
	 * @return string
	 */
	public function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<p";
		
		$return .= parent::__toString();
		
		
		/**
		 * Si han indicat una aliniació
		 */
		if (isset($this->Align))
		{
			$return .= " align=\"$this->Align\"";
		}		
						
		
		$return .= ">";
		
		$return .= $this->insereixContinguts();
		
		$return .= "</p>\n";
		
		return $return;
	}
		
}


/**
 * Definim l'element Imatge HTML
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 * 
 * @todo Implementar TABINDEX en aquest element
 */
class crtmlIMG extends crtmlBODYelement 
{
	/**
	 * URL de la imatge a mostrar
	 *
	 * @var string Required
	 */
	protected $Src;
	
	/**
	 * Petita descripció
	 *
	 * @var string Required
	 * @see peIMG()
	 */
	protected $Alt;

	/**
	 * URL per a una descripció estesa, complementa Alt
	 *
	 * @var string
	 */
	protected $LongDesc;
	
	/**
	 * Nom de la imatge per a scripting
	 *
	 * @var string
	 */
	protected $Name;
	
	/**
	 * Alçada de la imatge
	 *
	 * @var int
	 */
	protected $Height;
	
	/**
	 * Amplada de la imatge
	 *
	 * @var int
	 */
	protected $Width;
	
	
	/**
	 * Indiquem l'aliniació de la imatge respecte el text
	 * valors vàlids:
	 * bottom|middle|top|left|right 
	 *
	 * @var string
	 * @link http://www.w3.org/TR/html401/struct/objects.html#adef-align-IMG
	 */
	protected $Align;
	
	/**
	 * Establim la bora de la imatge en pixels.
	 * 
	 * Al crtml per omissió sempre serà zero.
	 *
	 * @var int
	 */
	protected $Border;
	
	/**
	 * Establim l'espai vertical que deixarem en blanc en pixels
	 *
	 * @var int
	 */
	protected $HSpace;
	
	/**
	 * Establim l'espai horitzontal que deixarem en blanc en pixels
	 *
	 * @var int
	 */
	protected $VSpace;
	
	protected $TabIndex;
	
	/**
	 * Constructor de la imatge.
	 * 
	 * Els dos paràmetres són els obligatoris segons el W3C
	 * @link http://www.w3.org/TR/html401/struct/objects.html#h-13.2
	 * 
	 * En cas de tractar-se d'imatges decoratives, indicar ""
	 *
	 * @param string $Src
	 * @param string $Alt
	 * @see $Src, $Alt
	 */
	function __construct($Src, $Alt)
	{
		$this->setSrc($Src);
		$this->setAlt($Alt);
	}
	
	/**
	 * Establim Src
	 *
	 * @param string $Src
	 * @see $Src
	 */
	function setSrc($Src)
	{
		$this->Src = $Src;
	}
	
	/**
	 * Establim Alt
	 *
	 * @param string $Alt
	 * @see $Alt
	 */
	function setAlt($Alt)
	{
		
		/**
   	 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 *	$this->Alt = htmlentities($Alt, ENT_COMPAT);
		 */
		$this->Alt = $Alt;
		
		/**
		 * Si no han especificat Title, li passem l'Alt.
		 */
		if (!(isset($this->Title)))
			$this->setTitle($Alt);
	}
	
	
	/**
	 * Establim LongDesc
	 *
	 * @param string $LongDesc
	 * @see $LongDesc
	 */
	function setLongDesc($LongDesc)
	{
		$this->LongDesc = $LongDesc;
	}
	
	/**
	 * Establim Name
	 *
	 * @param string $Name
	 * @see $Name
	 */
	function setName($Name)
	{
		$this->Name = $Name;
	}
	
	/**
	 * Establim Height
	 *
	 * @param string $Height
	 * @see $Height
	 */
	function setHeight($Height)
	{
		$this->Height = $Height;
	}
	
	/**
	 * Establim Width
	 *
	 * @param string $Width
	 * @see $Width
	 */
	function setWidth($Width)
	{
		$this->Width = $Width;
	}
	
	
	/**
	 * Establim Align bottom|middle|top|left|right
	 *
	 * @param string $Align
	 * @see $Align
	 */
	function setAlign($Align)
	{
		switch (strtolower($Align))
			{
				case 'bottom':
				case 'middle':
				case 'top':
				case 'left':
				case 'right':
					$this->Align = $Align;
					break;
				/**
				 * Si no és cap dels events permesos, donem un error fatal.
				 */
				default:
					throw new crtmlException("Align <b>$Align</b> not found.");
					break;
			}		

	}
	
	/**
	 * Establim Border
	 *
	 * @param string $Border
	 * @see $Border
	 */
	function setBorder($Border)
	{
		$this->Border = $Border;
	}
	
	/**
	 * Establim HSpace
	 *
	 * @param string $HSpace
	 * @see $HSpace
	 */
	function setHSpace($HSpace)
	{
		$this->HSpace = $HSpace;
	}
	
	/**
	 * Establim VSpace
	 *
	 * @param string $VSpace
	 * @see $VSpace
	 */
	function setVSpace($VSpace)
	{
		$this->VSpace = $VSpace;
	}
	
	/**
	 * Establim TabIndex
	 *
	 * @param string $TabIndex
	 * @see $TabIndex
	 */
	function setTabIndex($TabIndex)
	{
		$this->TabIndex = $TabIndex;
	}
		
	/**
	 * Renderitzem la imatge
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<img src=\"$this->Src\" alt=\"$this->Alt\"";
		
		$return .= parent::__toString();
		
		/**
		 * Si han indicat Align
		 */
		if (isset($this->Align))
		{
			$return .= " Align=\"$this->Align\"";
		}
		
		/**
		 * Si han indicat Border
		 */
		if (isset($this->Border))
		{
			$return .= " Border=\"$this->Border\"";
		}
				
		/**
		 * Si han indicat Height
		 */
		if (isset($this->Height))
		{
			$return .= " Height=\"$this->Height\"";
		}
		
		/**
		 * Si han indicat HSpace
		 */
		if (isset($this->HSpace))
		{
			$return .= " HSpace=\"$this->HSpace\"";
		}
		
		/**
		 * Si han indicat LongDesc
		 */
		if (isset($this->LongDesc))
		{
			$return .= " LongDesc=\"$this->LongDesc\"";
		}
		
		/**
		 * Si han indicat Name
		 */
		if (isset($this->Name))
		{
			$return .= " Name=\"$this->Name\"";
		}
		
		/**
		 * Si han indicat VSpace
		 */
		if (isset($this->VSpace))
		{
			$return .= " VSpace=\"$this->VSpace\"";
		}
		
		/**
		 * Si han indicat Width
		 */
		if (isset($this->Width))
		{
			$return .= " Width=\"$this->Width\"";
		}
				
			
		/**
		 * Si han indicat TabIndex
		 */
		if (isset($this->TabIndex))
		{
			$return .= " TabIndex=\"$this->TabIndex\"";
		}
		
		$return .= " />\n";
		
		return $return;
	}
}

/**
 * Definició de A com a enllaç
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlA extends crtmlBODYcontainer  
{
	
	protected $Text;
	
	protected $hRef;
	
	protected $Target;
	
	
	/**
	 * Construïm el link
	 * Per omissió el text alternatiu és el mateix que el text que mostrem.
	 *
	 * @param string $text
	 * @param string $hRef
	 * @param string $Title
	 * @param string $Target
	 * @return pePuntMenu
	 */
	function __construct($text, $hRef, $Title=null, $Target=null)
	{
		/**
		 * Afegim els events onfocus i onblur a aquesta entitat
		 */
		$this->Events['onfocus'] = "";
		$this->Events['onblur'] = "";
		
		/**
		 * Informem de les propietats de l'entitat
		 */
		$this->Text = $text;
		$this->addContingut($text);
		$this->sethRef($hRef);
		$this->setTitle($Title);
		$this->setTarget($Target);
		
	}

	
	/**
	 * Establim la URL a on apunta l'enllaç
	 *
	 * @param string $hRef
	 * @see $hRef
	 */
	function sethRef($hRef)
	{
		$this->hRef = $hRef;
	}
		
	/**
	 * Establim Target
	 *
	 * @param string $Target
	 * @see $Target
	 */
	function setTarget($Target)
	{
		$this->Target = $Target;
	}
	
	/**
	 * Establim Title
	 *
	 * @param string $Title
	 * @see $Title
	 */
	function setTitle($Title)
	{
		if (isset($Title))
		{
		/**
   	 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 * $this->Title = htmlentities($Title, ENT_COMPAT);
		 */
			$this->Title = $Title;
		}
		else 
		{
		/**
   	 	 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 * $this->Title = htmlentities($this->Text, ENT_COMPAT);
		 */
			if (!is_object($this->Text))
			{
				$this->Title = $this->Text;
			}
		}
		
	}
	
	
	function __toString()
	{
		/**
		 * Iniciem la cadena A
		 */
		$return = "<a href=\"$this->hRef\"";
		$return .= parent::__toString();
		
		/**
		 * Si han indicat Target
		 */
		if (isset($this->Target))
		{
			$return .= " Target=\"$this->Target\"";
		}
		
		

		$return .= ">";
		
		$return .= $this->insereixContinguts();
			
		$return .= "</a>";
		
		return $return;
	}
}

/**
 * Definició de l'entitat FORM
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlFORM extends crtmlBODYcontainer  
{
	/**
	 * URL de l'agent que processarà el formulari, si no indiquem rés,
	 * serà ell mateix.
	 *
	 * @var string
	 */
	protected $Action;
	
	/**
	 * Mètode HTTP emprat per a somtmetre les dades del formulari.
	 * Per omissió és GET
	 *
	 * @var string
	 */
	protected $Method = 'GET';
	
	/**
	 * This attribute specifies the content type used to submit the form 
	 * to the server (when the value of method is "post"). The default value 
	 * for this attribute is "application/x-www-form-urlencoded". 
	 * The value "multipart/form-data" should be used in combination 
	 * with the INPUT element, type="file". 
	 *
	 * @var string
	 */
	//protected $EncType = "application/x-www-form-urlencoded";
	protected $EncType = "multipart/form-data";
	
	/**
	 * This attribute specifies a comma-separated list of content types that 
	 * a server processing this form will handle correctly. User agents 
	 * may use this information to filter out non-conforming files when 
	 * prompting a user to select files to be sent to the server 
	 * (cf. the INPUT element when type="file"). 
	 *
	 * @var string
	 */
	protected $Accept;
	
	/**
	 * Nom del Frame on es mostrarà el resultat d'Action.
	 *
	 * @var string
	 */
	protected $Target;
	
	
	function __construct($Action, $Method = 'GET')
	{
		/**
		 * Afegim els events onsubmit i onreset a la llista d'events vàlids
		 */
		$this->Events['onsubmit'] = "";
		$this->Events['onreset'] = "";
		
		$this->setAction($Action);
		$this->setMethod($Method);
	}
	
	/**
	 * Establim Action
	 *
	 * @param string $Action
	 * @see $Action
	 */
	function setAction($Action)
	{
		$this->Action = $Action;
	}
	
	/**
	 * Establim Method
	 *
	 * @param string $Method
	 * @see $Method
	 */
	function setMethod($Method)
	{
		$this->Method = $Method;
	}
	/**
	 * Establim EncType
	 *
	 * @param string $EncType
	 * @see $EncType
	 */
	function setEncType($EncType)
	{
		$this->EncType = $EncType;
	}
	
	/**
	 * Establim Accept
	 *
	 * @param string $Accept
	 * @see $Accept
	 */
	function setAccept($Accept)
	{
		$this->Accept = $Accept;
	}
	
	/**
	 * Establim Target
	 *
	 * @param string $Target
	 * @see $Target
	 */
	function setTarget($Target)
	{
		$this->Target = $Target;
	}
	
	/**
	 * Afegim un contingut, objecte crtml
	 *
	 * @param object $Contingut
	 * @param string $pos
	 */
	function addContingut($Contingut, $pos=null)
	{
		if (isset($pos))
		{
			$this->Continguts[$pos] = $Contingut;
		}
		else 
		{
			$this->Continguts[] = $Contingut;
		}
	}

	/**
	 * Renderitzem el formulari
	 *
	 * @return string
	 */
	function __toString($formtag = true)
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		$return = "";
		
		if ($formtag) {
			$return = "\t<FORM Action=\"$this->Action\"";

			
			$return .= parent::__toString();
			
			/**
			 * Si han indicat Method
			 */
			if (isset($this->Method))
			{
				$return .= " Method=\"$this->Method\"";
			}
			
			/**
			 * Si han indicat EncType
			 */
			if (isset($this->EncType))
			{
				$return .= " EncType=\"$this->EncType\"";
			}
			
			/**
			 * Si han indicat Accept
			 */
			if (isset($this->Accept))
			{
				$return .= " Accept=\"$this->Accept\"";
			}
			
			/**
			 * Si han indicat Target
			 */
			if (isset($this->Target))
			{
				$return .= " Target=\"$this->Target\"";
			}
			
			$return .= ">\n";
		}
		
		/**
		 * Renderitzem els continguts
		 */
		$return .= $this->insereixContinguts();
		
		if ($formtag) {
			$return .= "\n\t</FORM>\n";
		}
		
		return $return;
	}
	
}

/**
 * Definició de l'entitat LEGEND
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlLEGEND extends crtmlBODYelement 
{
	protected $Text;
	protected $Align;
	protected $AccessKey;
	
	
	function __construct($Text)
	{
		$this->setText($Text);
	}
	
	/**
	 * Establim Text
	 *
	 * @param string $Text
	 * @see $Text
	 */
	function setText($Text)
	{
		$this->Text = $Text;
	}
	
	function getText()
	{
		return $this->Text;
	}
	
	/**
	 * Establim AccessKey
	 *
	 * @param string $AccessKey
	 * @see $AccessKey
	 */
	function setAccessKey($AccessKey)
	{
		$this->AccessKey = $AccessKey;
	}
	
	/**
	 * Establim Align top|bottom|left|right
	 *
	 * @param string $Align
	 * @see $Align
	 */
	function setAlign($Align)
	{
		switch (strtolower($Align))
			{
				case 'top':
				case 'bottom':
				case 'right':
				case 'left':
					$this->Align = $Align;
					break;
				/**
				 * Si no és cap dels events permesos, donem un error fatal.
				 */
				default:
					throw new crtmlException("Align <b>$Align</b> not found.");
					break;
			}		

	}
	
	/**
	 * Renderitzem LEGEND
	 *
	 * @return string
	 */
	function __toString()
	{
		$return = "\t\t<LEGEND";
		$return .= parent::__toString();
		$return .= ">$this->Text</LEGEND>\n";

		return $return;	
	}
}

/**
 * Definició de l'entitat FieldSet
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlFIELDSET extends crtmlBODYcontainer  
{	
	/**
	 * LEGEND del FieldSet, objecte crtml
	 *
	 * @var Object
	 */
	protected $Legend;
	
	/**
	 * Constructor del FieldSet
	 * 
	 * Permetem passar-li un text com a Legend, i la classe ja el convertirà a objecte crtmlLEGEND.
	 *
	 * @param object $Legend
	 * @return peFIELDSET
	 */
	function __construct($Legend=null)
	{
		$this->setLegend($Legend);
		
	}
	
	/**
	 * Establim Legend
	 *
	 * @param string $Legend
	 * @see $Legend
	 */
	function setLegend($Legend)
	{
		If (is_object($Legend))
		{
			$this->Legend = $Legend;
		}
		else 
		{
			$this->Legend = new crtmlLEGEND($Legend);
		}
	}
	
	function getLegend()
	{
		return $this->Legend->getText();
	}
	

	/**
	 * Renderitzem el FieldSet
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		$return = "\t<FIELDSET";
		$return .= parent::__toString();
		$return .= ">\n";
		
		$return .= (string) $this->Legend;
		
		$return .= $this->insereixContinguts();
		
		$return .= "\n\t</FIELDSET>\n";
		
		return $return;
	}

}

/**
 * Definició de l'entitat Button
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlBUTTON extends crtmlBODYcontainer  
{
	/**
	 * This attribute assigns the control name.
	 *
	 * @var string
	 */
	protected $Name;
	
	/**
	 * This attribute assigns the initial value to the button.
	 *
	 * @var string
	 */
	protected $Value;
	
	/**
	 * This attribute declares the type of the button. Possible values: 
	 * 	submit: Creates a submit button. This is the default value. 
	 * 	reset: Creates a reset button. 
	 * 	button: Creates a push button. 
	 *
	 * @var string
	 */
	protected $Type;
	
	/**
	 * Ordre de tabulació
	 *
	 * @var Int
	 */
	protected $TabIndex;
	
	/**
	 * Tecla d'accès ràpid
	 *
	 * @var string
	 */
	protected $AccessKey;
	

	
	/**
	 * Constructor del botó
	 *
	 * @param string $Name
	 * @param string $Type
	 * @return peBUTTON
	 */
	function __construct($Name, $Type = 'submit')
	{
		$this->setName($Name);
		$this->setType($Type);
	}
	
	/**
	 * Establim Name
	 *
	 * @param string $Name
	 * @see $Name
	 */
	function setName($Name)
	{
		$this->Name = $Name;
	}
	
	/**
	 * Establim Value
	 *
	 * @param string $Value
	 * @see $Value
	 */
	function setValue($Value)
	{
		$this->Value = $Value;
	}
	
	/**
	 * Establim Type
	 *
	 * @param string $Type
	 * @see $Type
	 */
	function setType($Type)
	{
	
		switch (strtolower($Type))
			{
				case 'submit':
				case 'reset':
				case 'button':
					$this->Type = $Type;
					break;
				/**
				 * Si no és cap dels tipus permesos, donem un error fatal.
				 */
				default:
					throw new crtmlException("Button type <b>$Type</b> no valid.");
					break;
			}		
		
	}
	
	/**
	 * Establim TabIndex
	 *
	 * @param string $TabIndex
	 * @see $TabIndex
	 */
	function setTabIndex($TabIndex)
	{
		$this->TabIndex = $TabIndex;
	}
	
	/**
	 * Establim AccessKey
	 *
	 * @param string $AccessKey
	 * @see $AccessKey
	 */
	function setAccessKey($AccessKey)
	{
		$this->AccessKey = $AccessKey;
	}
	

	/**
	 * Renderitzem el botó
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		$return = "\t<BUTTON";
		$return .= parent::__toString();
		
		/**
		 * Si han indicat Name
		 */
		if (isset($this->Name))
		{
			$return .= " Name=\"$this->Name\"";
		}
		
		/**
		 * Si han indicat Value el posem, si no posem el Name
		 */
		if (isset($this->Value))
		{
			$return .= " Value=\"$this->Value\"";
		}

		
		/**
		 * Si han indicat Type
		 */
		if (isset($this->Type))
		{
			$return .= " Type=\"$this->Type\"";
		}
		
		/**
		 * Si han indicat TabIndex
		 */
		if (isset($this->TabIndex))
		{
			$return .= " TabIndex=\"$this->TabIndex\"";
		}
		
		/**
		 * Si han indicat AccessKey
		 */
		if (isset($this->AccessKey))
		{
			$return .= " AccessKey=\"$this->AccessKey\"";
		}
		
		$return .= ">\n";
		
		/**
		 * Renderitzem els continguts
		 */
		$n = $this->insereixContinguts();
		$return .= $n;
		
		/**
		 * Si no hi havia cap contingut, posem com a text el nom del botó que és obligatori.
		 */
		if ($n=='')
		{
			$return .= $this->Name;
		}
		
		
		$return .= "\n\t</BUTTON>\n";
		
		return $return;
	}	
}

/**
 * Definició de l'entitat Option
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlOPTION extends crtmlBODYelement 
{
	/**
	 * Indiquem si l'opció està triada
	 *
	 * @var bool
	 */
	protected $Selected;
	
	/**
	 * Etiqueta de l'opció
	 *
	 * @var string
	 */
	protected $Label;
	
	/**
	 * Valor
	 *
	 * @var string
	 */
	protected $Value;
	
	/**
	 * Text de l'opció
	 *
	 * @var string
	 */
	protected $Text;
	
	/**
	 * Constructor de la classe
	 *
	 * @param string $Text
	 * @return peOPTION
	 */
	function __construct($Text, $val = null)
	{
		$this->setText($Text);
		
		if ($val != null)
		{
			$this->setValue($val);
		}
	}

	/**
	 * Establim Selected
	 *
	 * @param string $Selected
	 * @see $Selected
	 */
	function setSelected($Selected)
	{
		$this->Selected = $Selected;
	}
	
	/**
	 * Establim Label
	 *
	 * @param string $Label
	 * @see $Label
	 */
	function setLabel($Label)
	{
		$this->Label = $Label;
	}
	
	/**
	 * Establim Value
	 *
	 * @param string $Value
	 * @see $Value
	 */
	function setValue($Value)
	{
		$this->Value = $Value;
	}

	/**
	 * Establim Text
	 *
	 * @param string $Text
	 * @see $Text
	 */
	function setText($Text)
	{
		$this->Text = $Text;
	}
	
	/**
	 * Renderitzem l'opció
	 *
	 * @return string
	 */
	function __toString()
	{
		$return = "\t\t<OPTION";
		$return .= parent::__toString();
		
		/**
		 * Si han indicat Selected
		 */
		if (isset($this->Selected))
		{
			$return .= " Selected";
		}
	
		/**
		 * Si han indicat Value
		 */
		if (isset($this->Value))
		{
			$return .= " Value=\"$this->Value\"";
		}
		
		$return .= ">" . $this->Text . "</OPTION>\n";
		
		return $return;
	}
	
}

/**
 * Definició de l'entitat HTML SELECT
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlSELECT extends crtmlBODYelement 
{
	
	/**
	 * Nom del camp
	 *
	 * @var string
	 */
	protected $Name;
	
	/**
	 * Número de registres que es mostren
	 *
	 * @var int
	 */
	protected $Size;
	
	/**
	 * Permetem múltiple selecció
	 *
	 * @var boolean
	 */
	protected $Multiple = false;
	
	/**
	 * Possició de tabulador
	 *
	 * @var int
	 */
	protected $TabIndex;
	
	/**
	 * Objectes de tipus peOPTION que composen aquesta SELECT
	 *
	 * @var Array
	 */
	protected $Opcions = Array();
	
	
	function __construct($Name)
	{
		/**
		 * Afegim els events onfocus, onblur i onchange a aquesta entitat
		 */
		$this->Events['onfocus'] = "";
		$this->Events['onblur'] = "";
		$this->Events['onchange'] = "";
		
		$this->setName($Name);
	}
	
	/**
	 * Establim Name
	 *
	 * @param string $Name
	 * @see $Name
	 */
	function setName($Name)
	{
		$this->Name = $Name;
	}
	
	/**
	 * Establim Size
	 *
	 * @param string $Size
	 * @see $Size
	 */
	function setSize($Size)
	{
		$this->Size = $Size;
	}
	
	/**
	 * Establim Multiple selecció
	 *
	 * @param string $Multiple
	 * @see $Multiple
	 */
	function setMultiple($Multiple)
	{
		$this->Multiple = $Multiple;
	}
	
	/**
	 * Establim TabIndex
	 *
	 * @param string $TabIndex
	 * @see $TabIndex
	 */
	function setTabIndex($TabIndex)
	{
		$this->TabIndex = $TabIndex;
	}
	
	/**
	 * Afegim un objecte peOPTION a la llista
	 *
	 * @param Objecte $Opcio
	 */
	function addOpcio($Opcio)
	{
		$this->Opcions[] = $Opcio;
		
	}
	
	/**
	 * Renderitzem la Select
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		if (isset($this->spanId) and $this->spanId != '') {
			$return = "<span id=\"" . $this->spanId . "\">";
		}
		else {
			$return = "";
		}
		$return .= "\t<SELECT";
		$return .= parent::__toString();
		
		/**
		 * Si han indicat Name
		 */
		if (isset($this->Name))
		{
			$return .= " Name=\"$this->Name\"";
		}
		
		/**
		 * Si han indicat valors múltiples.
		 */
		if (isset($this->Multiple))
		{
			$return .= " $this->Multiple";
		}

		
		/**
		 * Si han indicat TabIndex
		 */
		if (isset($this->TabIndex))
		{
			$return .= " TabIndex=\"$this->TabIndex\"";
		}
		
		/**
		 * Si han indicat AccessKey
		 */
		if (isset($this->AccessKey))
		{
			$return .= " AccessKey=\"$this->AccessKey\"";
		}
		
		$return .= ">\n";
		
		/**
		 * Renderitzem els continguts
		 */
		foreach ($this->Opcions as $Opcio) 
		{
			$return .= (string) $Opcio;
		}
		
		
		
		$return .= "\n\t</SELECT>\n";

		if (isset($this->spanId) and $this->spanId != '') {
			$return .= "</span>";
		}
		return $return;
	}
	
}

/**
 * Definició de l'entitat HTML TEXTAREA
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlTEXTAREA extends crtmlBODYcontainer  
{
	/**
	 * Nom del camp
	 *
	 * @var string
	 */
	protected $Name;
	
	/**
	 * Línies edició
	 *
	 * @var int
	 */
	protected $Rows=10;
	
	/**
	 * Columnes edició
	 *
	 * @var int
	 */
	protected $Cols=40;
	
	/**
	 * Només lectura?
	 *
	 * @var boolean
	 */
	protected $ReadOnly = false;
	
	/**
	 * Indexació tabulador
	 *
	 * @var int
	 */
	protected $TabIndex;
	
	/**
	 * AccessKey
	 *
	 * @var string
	 */
	protected $AccessKey;
	
	function __construct($Name)
	{
		/**
		 * Afegim els events onfocus, onblur i onchange a aquesta entitat
		 */
		$this->Events['onfocus'] = "";
		$this->Events['onblur'] = "";
		$this->Events['onchange'] = "";
		$this->Events['onselect'] = "";
		
		$this->setName($Name);
	}
	
	/**
	 * Establim Name
	 *
	 * @param string $Name
	 * @see $Name
	 */
	function setName($Name)
	{
		$this->Name = $Name;
	}
	
	/**
	 * Establim Rows
	 *
	 * @param string $Rows
	 * @see $Rows
	 */
	function setRows($Rows)
	{
		$this->Rows = $Rows;
	}
	
	/**
	 * Establim Cols
	 *
	 * @param string $Cols
	 * @see $Cols
	 */
	function setCols($Cols)
	{
		$this->Cols = $Cols;
	}
	
	/**
	 * Establim ReadOnly
	 *
	 * @param string $ReadOnly
	 * @see $ReadOnly
	 */
	function setReadOnly($ReadOnly)
	{
		$this->ReadOnly = $ReadOnly;
	}
	
	/**
	 * Establim TabIndex
	 *
	 * @param string $TabIndex
	 * @see $TabIndex
	 */
	function setTabIndex($TabIndex)
	{
		$this->TabIndex = $TabIndex;
	}
	
	/**
	 * Establim AccessKey
	 *
	 * @param string $AccessKey
	 * @see $AccessKey
	 */
	function setAccessKey($AccessKey)
	{
		$this->AccessKey = $AccessKey;
	}

	
	/**
	 * Renderitzem la TEXTAREA
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		$return = "\t<TEXTAREA";
		$return .= parent::__toString();
		
		/**
		 * col·loquem els rows i les cols
		 */
		$return .= " rows=\"" . $this->Rows . "\" cols=\"" . $this->Cols . "\" ";
		
		/**
		 * Si han indicat Name
		 */
		if (isset($this->Name))
		{
			$return .= " Name=\"$this->Name\"";
		}
		
		
		/**
		 * Si han indicat ReadOnly
		 */
		if ($this->ReadOnly)
		{
			$return .= " $this->ReadOnly";
		}

		
		/**
		 * Si han indicat TabIndex
		 */
		if (isset($this->TabIndex))
		{
			$return .= " TabIndex=\"$this->TabIndex\"";
		}
		
		/**
		 * Si han indicat AccessKey
		 */
		if (isset($this->AccessKey))
		{
			$return .= " AccessKey=\"$this->AccessKey\"";
		}
		
		$return .= ">\n";
		
		/**
		 * Renderitzem els continguts
		 */
		$return .= $this->insereixContinguts();
		
		$return .= "</TEXTAREA>\n";
		
		return $return;
	}		
}

/**
 * Definció de l'entitat HTML INPUT
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlINPUT extends crtmlBODYelement 
{
	/**
	 * text|password|checkbox|radio|submit|reset|file|hidden|image|button [CI] 
	 * This attribute specifies the type of control to create. The default value for this attribute is "text". 
	 *
	 * @var string
	 */
	protected $Type;
	
	/**
	 * Nom del camp
	 *
	 * @var string
	 */
	protected $Name;
	
	/**
	 * This attribute specifies the initial value of the control. It is optional except when the type attribute has the value "radio" or "checkbox". 
	 *
	 * @var string
	 */
	protected $Value;
	
	
	/**
	 * When the type attribute has the value "radio" or "checkbox", this boolean attribute specifies that the button is on. 
	 * User agents must ignore this attribute for other control types. 
	 *
	 * @var boolean
	 */
	protected $Checked=false;
	
	/**
	 * Indiquem si és de només lectura
	 *
	 * @var boolean
	 */
	protected $ReadOnly=false;
	
	/**
	 * This attribute tells the user agent the initial width of the control. 
	 * The width is given in pixels except when type attribute has the value "text" or "password". 
	 * In that case, its value refers to the (integer) number of characters. 
	 *
	 * @var int
	 */
	protected $Size;
	
	/**
	 * When the type attribute has the value "text" or "password", this attribute specifies the maximum number of characters the user may enter. 
	 * This number may exceed the specified size, in which case the user agent should offer a scrolling mechanism. 
	 * The default value for this attribute is an unlimited number. 
	 *
	 * @var int
	 */
	protected $MaxLength;
	
	/**
	 * When the type attribute has the value "image", this attribute specifies the location of the image to be used to decorate the graphical submit button.
	 *
	 * @var string
	 */
	protected $Src;
	
	/**
	 * Text alternatiu de la imatge
	 *
	 * @var string
	 */
	protected $Alt;
	
	/**
	 * This attribute specifies the position of the current element in the tabbing order for the current document. 
	 * This value must be a number between 0 and 32767. User agents should ignore leading zeros. 
	 *
	 * @var int
	 */
	protected $TabIndex;
	
	/**
	 * This attribute assigns an access key to an element. An access key is a single character from the document character set. 
	 * Note. Authors should consider the input method of the expected reader when specifying an accesskey. 
	 *
	 * @var string
	 */
	protected $AccessKey;
	
	/**
	 * This attribute specifies a comma-separated list of content types that a server processing this form will handle correctly. 
	 * User agents may use this information to filter out non-conforming files when prompting a user 
	 * to select files to be sent to the server (cf. the INPUT element when type="file"). 
	 *
	 * @var string
	 */
	protected $Accept;
	
	
	function __construct($Name)
	{
		/**
		 * Afegim els events onfocus, onblur i onchange a aquesta entitat
		 */
		$this->Events['onfocus'] = "";
		$this->Events['onblur'] = "";
		$this->Events['onchange'] = "";
		$this->Events['onselect'] = "";
		
		$this->setName($Name);
	}
	
	/**
	 * Establim Type text|password|checkbox|radio|submit|reset|file|hidden|image|button 
	 *
	 * @param string $Type
	 * @see $Type
	 */
	function setType($Type)
	{
	
		switch (strtolower($Type))
			{
				case 'text':
				case 'password':
				case 'checkbox':
				case 'radio':
				case 'submit':
				case 'reset':
				case 'file':
				case 'hidden':
				case 'image':
				case 'button':	
					$this->Type = $Type;
					break;
				/**
				 * Si no és cap dels tipus permesos, donem un error fatal.
				 */
				default:
					throw new crtmlException("Input type <b>$Type</b> no valid.");
					break;
			}		
		
	}
	
	/**
	 * Establim Name
	 *
	 * @param string $Name
	 * @see $Name
	 */
	function setName($Name)
	{
		$this->Name = $Name;
	}
	
	/**
	 * Establim Value
	 *
	 * @param string $Value
	 * @see $Value
	 */
	function setValue($Value)
	{
		$this->Value = $Value;
	}
	
	/**
	 * Establim Checked
	 *
	 * @param string $Checked
	 * @see $Checked
	 */
	function setChecked($Checked)
	{
		$this->Checked = $Checked;
	}
	
	/**
	 * Establim ReadOnly
	 *
	 * @param string $ReadOnly
	 * @see $ReadOnly
	 */
	function setReadOnly($ReadOnly)
	{
		/**
		 * En el cas de que es passi el paràmetre
		 * true, indiquem el text.
		 */
		if ($ReadOnly === true)
		{
				$this->ReadOnly = "READONLY";
				/**
				 * Afegim un event per a que
				 * sigui realment READONLY
				 */
				$this->addEvent('onfocus', 'this.blur();');
		}
		else
		{
			$this->ReadOnly = $ReadOnly;
		}
	}
	
	/**
	 * Establim Size
	 *
	 * @param string $Size
	 * @see $Size
	 */
	function setSize($Size)
	{
		$this->Size = $Size;
	}
	
	/**
	 * Establim MaxLength
	 *
	 * @param string $MaxLength
	 * @see $MaxLength
	 */
	function setMaxLength($MaxLength)
	{
		$this->MaxLength = $MaxLength;
	}
	/**
	 * Establim Src
	 *
	 * @param string $Src
	 * @see $Src
	 */
	function setSrc($Src)
	{
		$this->Src = $Src;
	}
	/**
	 * Establim Alt
	 *
	 * @param string $Alt
	 * @see $Alt
	 */
	function setAlt($Alt)
	{
		$this->Alt = $Alt;
	}
	
	/**
	 * Establim TabIndex
	 *
	 * @param string $TabIndex
	 * @see $TabIndex
	 */
	function setTabIndex($TabIndex)
	{
		$this->TabIndex = $TabIndex;
	}
	
	/**
	 * Establim AccessKey
	 *
	 * @param string $AccessKey
	 * @see $AccessKey
	 */
	function setAccessKey($AccessKey)
	{
		$this->AccessKey = $AccessKey;
	}
	/**
	 * Establim Accept
	 *
	 * @param string $Accept
	 * @see $Accept
	 */
	function setAccept($Accept)
	{
		$this->Accept = $Accept;
	}
	

	/**
	 * Renderitzem el camp
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		$return = "\t<INPUT";
		$return .= parent::__toString();
		
		/**
		 * Si han indicat Type
		 */
		if (isset($this->Type))
		{
			$return .= " Type=\"$this->Type\"";
		}
		
		/**
		 * Si han indicat Name
		 */
		if (isset($this->Name))
		{
			$return .= " Name=\"$this->Name\"";
		}
		
		/**
		 * Si han indicat Value
		 */
		if (isset($this->Value))
		{
			$return .= " Value=\"$this->Value\"";
		}
		/**
		 * Si han indicat ReadOnly
		 */
		if ($this->ReadOnly)
		{
			$return .= " $this->ReadOnly";
		}
		
		/**
		 * Si han indicat Checked
		 */
		if ($this->Checked)
		{
			$return .= " CHECKED";
		}		

		/**
		 * Si han indicat Size
		 */
		if (isset($this->Size))
		{
			$return .= " Size=\"$this->Size\"";
		}
		
		/**
		 * Si han indicat MaxLength
		 */
		if (isset($this->MaxLength))
		{
			$return .= " MaxLength=\"$this->MaxLength\"";
		}
		
		/**
		 * Si han indicat Src
		 */
		if (isset($this->Src))
		{
			$return .= " Src=\"$this->Src\"";
		}
		
		/**
		 * Si han indicat Alt
		 */
		if (isset($this->Alt))
		{
			$return .= " Alt=\"$this->Alt\"";
		}
		
		
		/**
		 * Si han indicat TabIndex
		 */
		if (isset($this->TabIndex))
		{
			$return .= " TabIndex=\"$this->TabIndex\"";
		}
		
		/**
		 * Si han indicat AccessKey
		 */
		if (isset($this->AccessKey))
		{
			$return .= " AccessKey=\"$this->AccessKey\"";
		}
		
		$return .= " />\n";
		
		return $return;
	}			
}

/**
 * Definició de l'entitat HTML LABEL
 *
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlLABEL extends crtmlBODYcontainer  
{
	protected $For;
	protected $AccessKey;
	protected $Label;
	
	/**
	 * Constructor amb un contingut
	 *
	 * @param object $Contingut
	 * @return peLABEL
	 */
	function __construct($Contingut)
	{
		/**
		 * Afegim els events onfocus, onblur i onchange a aquesta entitat
		 */
		$this->Events['onfocus'] = "";
		$this->Events['onblur'] = "";
		
		$this->Label = $Contingut;
		
		$this->addContingut($Contingut);

	}
	
	/**
	 * Establim For
	 *
	 * @param string $For
	 * @see $For
	 */
	function setFor($For)
	{
		$this->For = $For;
	}
	
	function getLabel()
	{
		return  $this->Label;
	}

	function getContinguts()
	{
		return $this->Continguts;
		
	}
	/**
	 * Establim AccessKey
	 *
	 * @param string $AccessKey
	 * @see $AccessKey
	 */
	function setAccessKey($AccessKey)
	{
		$this->AccessKey = $AccessKey;
	}
	
	
	function __toString()
	{
		/**
		 * Iniciem l'element amb els paràmetres obligatoris i els
		 * d'una entitat HTML genèrica.
		 */
		$return = "\t<LABEL";
		$return .= parent::__toString();
		
		/**
		 * Si han indicat For
		 */
		if (isset($this->For))
		{
			$return .= " For=\"$this->For\"";
			
			if (!isset($this->Id))
				$return .= " Id=\"literal_de_$this->For\"";
		}
		
		/**
		 * Si han indicat AccessKey
		 */
		if (isset($this->AccessKey))
		{
			$return .= " AccessKey=\"$this->AccessKey\"";
		}
		
		$return .= ">";

		
		$return .= $this->insereixContinguts();
		
		
		$return .= "</LABEL>\n";
		return $return;
	}
}

/**
 * Definim l'element DIV HTML
 *
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlDIV extends crtmlBODYcontainer  
{
	/**
	 * Text que conté el element.
	 *
	 * @var string Required
	 */
	protected $Text;
	
	/**
	 * Constructor de la classe peDIV.
	 *
	 * @param string $id
	 * @param string $text
	 * @param string $classe
	 */
	function __construct($id, $text =null, $classe=null) 
	{
		
		$this->setId($id);
		$this->setText($text);
		$this->setClass($classe);
	}
	
	/**
	 * Establim Text esborrant qualsevol contingut anterior.
	 *
	 * @param string $Text
	 * @see $Text
	 */
	function setText($Text)
	{
	 	/**
   	 	 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 * $this->Text = htmlentities($Text, ENT_COMPAT);
		 */
		if ($Text != '')
		{
			$this->Continguts[''] = $Text;
		}
	}

	

			
	/**
	 * Renderitzem el text
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<DIV";
		
		$return .= parent::__toString();
		
		$return .= ">";
		
		$return .= $this->insereixContinguts();
		
		$return .= "</DIV>\n";
		
		return $return;
	}

}

class crtmlSPAN extends crtmlBODYcontainer 
{
	
	/**
	 * Text que conté el element.
	 *
	 * @var string Required
	 */
	protected $Text;
	
	/**
	 * Constructor de la classe peDIV.
	 *
	 * @param string $id
	 * @param string $text
	 * @param string $classe
	 */
	function __construct($id, $text =null, $classe=null) 
	{
		
		$this->setId($id);
		$this->setText($text);
		$this->setClass($classe);
	}
	
	/**
	 * Establim Text esborrant qualsevol contingut anterior.
	 *
	 * @param string $Text
	 * @see $Text
	 */
	function setText($Text)
	{
	 	/**
   	 	 * Fins que no actualizem a PHP6 que te suport utf-8 en natiu, no 
		 * emprarem htmlentities doncs no funciona correctament.
		 * $this->Text = htmlentities($Text, ENT_COMPAT);
		 */
		if ($Text != '')
		{
			$this->Continguts[''] = $Text;
		}
	}
	
	function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<SPAN";
		
		$return .= parent::__toString();
		
		$return .= ">";
		
		$return .= $this->insereixContinguts();
		
		$return .= "</SPAN>\n";
		
		return $return;
	}
}

/**
 * Definim l'element HEAD HTML
 *
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlHEAD extends crtmlBODYcontainer 
{


	function __construct() 
	{
	}

	
			
	/**
	 * Renderitzem el HEAD
	 *
	 * @return string
	 */
	function __toString()
	{
		/**
		 * Inicialitzem el que tornarem
		 */
		$return = "";
		
		/**
		 * Iniciem l'objecte HTML, passem dels dos únics paràmetres que pot tenir LANG i DIR
		 * pq no es fa servir mai.
		 * 
		 * Mantenim però separada la instrucció per a quan ho fem
		 * 
		 * @todo implementar LANG i DIR com a elements universals.
		 */
		$return .= "<HEAD";
		$return .= ">\n";
		
		/**
		 * Afegim els continguts
		 */
		$return .= $this->insereixContinguts();

		
		$return .= "</HEAD>\n";
		
		return $return;
	}
	
	/**
	 * Permetem indicar el títol des d'aquest mateix objecte.
	 *
	 * @param unknown_type $Title
	 */
	public function set_Title($Title)
	{
		$this->addContingut(new crtmlTITLE($Title));
	}

}

/**
 * Objecte Title dins el HEAD
 * 
 * @link http://www.w3.org/TR/html4/struct/global.html#edef-TITLE
 *
 */
class crtmlTITLE
{
	protected $Title;
	
	
	public function __construct($Title)
	{
		$this->set_Title($Title);	
	}
	
	/**
	 * Assignem un valor a $Title
	 *
	 * @param string $Title
	 */
	public function set_Title($Title)
	{
			$this->Title = $Title;
	}
	
	public function __toString()
	{
		$return = "<TITLE";
		$return .= ">";
		
		$return .= $this->Title;
		
		$return .= "</TITLE>\n";
		
		return $return;
	}
}

/**
 * Definim objecte META
 * 
 * @link http://www.w3.org/TR/html4/struct/global.html#edef-META
 *
 */
class crtmlMETA
{
	protected $httpEquiv;
	
	protected $name;
	
	protected $content;
	
	protected $scheme;
	
	public function __construct($content)
	{
		$this->set_content($content);
	}
	
	
	/**
	 * Assignem un valor a $httpEquiv
	 *
	 * @param string $httpEquiv
	 */
	public function set_httpEquiv($httpEquiv)
	{
			$this->httpEquiv = $httpEquiv;
	}
	/**
	 * Assignem un valor a $name
	 *
	 * @param string $name
	 */
	public function set_name($name)
	{
			$this->name = $name;
	}
	
	/**
	 * Assignem un valor a $content
	 *
	 * @param string $content
	 */
	public function set_content($content)
	{
			$this->content = $content;
	}
	
	/**
	 * Assignem un valor a $scheme
	 *
	 * @param string $scheme
	 */
	public function set_scheme($scheme)
	{
			$this->scheme = $scheme;
	}
	
	public function __toString()
	{
		$return = '<META content="' . $this->content . '" ';
		
		/**
		 * Si han indicat name
		 */
		if (isset($this->name))
		{
			$return .= " NAME=\"$this->name\"";
		}	
		
		/**
		 * Si han indicat httpEquiv
		 */
		if (isset($this->httpEquiv))
		{
			$return .= " HTTP-EQUIV=\"$this->httpEquiv\"";
		}	

		/**
		 * Si han indicat scheme
		 */
		if (isset($this->scheme))
		{
			$return .= " SCHEME=\"$this->scheme\"";
		}	
		
		$return .= "/>";
		
		return $return;
	}
	
	
}

/**
 * Objecte STYLE de la capçalera
 * 
 * @link http://www.w3.org/TR/html401/present/styles.html#h-14.2.3
 *
 */
class crtmlSTYLE extends crtmlBODYcontainer 
{
	protected $media;
	
	protected $type;
	
	protected $title;
	
	public function __construct($type = "text/css")
	{
		$this->Continguts = Array();
		
		$this->set_type($type);
	}
	
	/**
	 * Assignem un valor a $media
	 *
	 * @param string $media
	 */
	public function set_media($media)
	{
			$this->media = $media;
	}
	
	/**
	 * Assignem un valor a $type
	 *
	 * @param string $type
	 */
	public function set_type($type)
	{
			$this->type = $type;
	}
	
	/**
	 * Assignem un valor a $title
	 *
	 * @param string $title
	 */
	public function set_title($title)
	{
			$this->title = $title;
	}
	
	/**
	 * Renderitzem l'objecte
	 *
	 */
	public function __toString()
	{

		$return = "<STYLE";
		
		/**
		 * Si han indicat type
		 */
		if (isset($this->type))
		{
			$return .= " type=\"$this->type\"";
		}	
		
		/**
		 * Si han indicat media
		 */
		if (isset($this->media))
		{
			$return .= " media=\"$this->media\"";
		}	
		
		/**
		 * Si han indicat title
		 */
		if (isset($this->title))
		{
			$return .= " title=\"$this->title\"";
		}	
		
		$return .= ">\n";
		
		/**
		 * Afegim els continguts
		 */
		$return .= $this->insereixContinguts();
		
		$return .= "</STYLE>\n";
		
		return $return;
		
	}
}

class crtmlLINK
{
	
	protected $charset;
	
	protected $href;
	
	protected $hreflang;
	
	protected $type;
	
	protected $rel;
	
	protected $rev;
	
	protected $media;
	
	
	public function __construct($href)
	{
		$this->set_href($href);
	}
	
	/**
	 * Assignem un valor a $charset
	 *
	 * @param string $charset
	 */
	public function set_charset($charset)
	{
			$this->charset = $charset;
	}
	
	/**
	 * Assignem un valor a $href
	 *
	 * @param string $href
	 */
	public function set_href($href)
	{
			$this->href = $href;
	}
	
	/**
	 * Assignem un valor a $hreflang
	 *
	 * @param string $hreflang
	 */
	public function set_hreflang($hreflang)
	{
			$this->hreflang = $hreflang;
	}
	
	/**
	 * Assignem un valor a $type
	 *
	 * @param string $type
	 */
	public function set_type($type)
	{
			$this->type = $type;
	}
	
	/**
	 * Assignem un valor a $rel
	 *
	 * @param string $rel
	 */
	public function set_rel($rel)
	{
			$this->rel = $rel;
	}
	
	/**
	 * Assignem un valor a $rev
	 *
	 * @param string $rev
	 */
	public function set_rev($rev)
	{
			$this->rev = $rev;
	}
	
	/**
	 * Assignem un valor a $media
	 *
	 * @param string $media
	 */
	public function set_media($media)
	{
			$this->media = $media;
	}
	
	public function __toString()
	{
		$return = "<LINK href=\"$this->href\" ";
		
		/**
		 * Si han indicat charset
		 */
		if (isset($this->charset))
		{
			$return .= " charset=\"$this->charset\"";
		}	
		
		/**
		 * Si han indicat hreflang
		 */
		if (isset($this->hreflang))
		{
			$return .= " hreflang=\"$this->hreflang\"";
		}	
		
		
		/**
		 * Si han indicat type
		 */
		if (isset($this->type))
		{
			$return .= " type=\"$this->type\"";
		}	
		
		/**
		 * Si han indicat rel
		 */
		if (isset($this->rel))
		{
			$return .= " rel=\"$this->rel\"";
		}	
		
		/**
		 * Si han indicat rev
		 */
		if (isset($this->rev))
		{
			$return .= " rev=\"$this->rev\"";
		}	
		
		/**
		 * Si han indicat media
		 */
		if (isset($this->media))
		{
			$return .= " media=\"$this->media\"";
		}	
		
		
		$return .= ">\n";
		
		return $return;
	}
}

class crtmlSCRIPT
{
	
	protected $charset;
	
	protected $type;
	
	protected $language;
	
	protected $src;
	
	protected $defer;
	
	protected $code;
	
	
	public function __construct($language = "JavaScript")
	{
		$this->set_language($language);
	}
	
	
	/**
	 * Assignem un valor a $language
	 *
	 * @param string $language
	 */
	public function set_language($language)
	{
			$this->language = $language;
			$this->set_type("text/" . $language);
	}
	/**
	 * Assignem un valor a $charset char encoding of linked resource
	 *
	 * @param string $charset
	 */
	public function set_charset($charset)
	{
			$this->charset = $charset;
	}

	/**
	 * Assignem un valor a $type content type of script language 
	 *
	 * @param string $type
	 */
	public function set_type($type)
	{
			$this->type = $type;
	}
	
	/**
	 * Assignem un valor a $src URI for an external script
	 *
	 * @param string $src
	 */
	public function set_src($src)
	{
			$this->src = $src;
	}
	
	/**
	 * Assignem un valor a $defer  UA may defer execution of script
	 *
	 * @param string $defer
	 */
	public function set_defer($defer)
	{
			$this->defer = $defer;
	}
	
	/**
	 * Assignem un valor a $code
	 *
	 * @param string $code
	 */
	public function set_code($code)
	{
			$this->code = $code;
	}
	
	public function __toString()
	{
		$return = "<SCRIPT ";
		
		/**
		 * Si han indicat charset
		 */
		if (isset($this->charset))
		{
			$return .= " charset=\"$this->charset\"";
		}	
			
		/**
		 * Si han indicat type
		 */
		if (isset($this->type))
		{
			$return .= " type=\"$this->type\"";
		}	
		
		/**
		 * Si han indicat defer
		 */
		if (isset($this->defer))
		{
			$return .= " defer=\"$this->defer\"";
		}	
		
		/**
		 * Si han indicat src
		 */
		if (isset($this->src))
		{
			$return .= " src=\"$this->src\"";
		}			
		
		$return .= ">\n";
		
		/**
		 * Si han indicat code
		 */
		if (isset($this->code))
		{
			$return .= $this->code;
		}	
		
		$return .= "</SCRIPT>\n";
		
		return $return;
	}
}


class crtmlBODY extends crtmlBODYcontainer 
{
	protected $Events = Array(	'onload' => "",
								'onunload' => "");
	
	public function __construct()
	{
	
	}
	
	/**
	 * Afegim un event al element  onload|onunload
	 *
	 * @param string $event
	 * @param string $action
	 * @see $Events
	 */
	function addEvent($event, $accio)
	{
		if (isset($this->Events[strtolower($event)]))
		{
				$this->Events[$event] = $accio;
		}
		else 
		{
			throw new crtmlException("Event <b>$event</b> not found.");
		}
		
	}
	
	
	public function __toString()
	{
		$return = "<BODY ";
		
		/**
		 * Si han indicat Events
		 */
		if (isset($this->Events))
		{
			foreach ($this->Events as $Event => $Accio) 
			{
				if ($Accio != "")
				{
					$return .= " $Event=\"$Accio\"";
				}
			}
		}
		
		$return .= ">\n";
		
		/**
		 * Afegim els continguts
		 */
		$return .= $this->insereixContinguts();

		$return .= "</BODY>\n";

		return $return;
	}
	

}


class crtmlHTML
{
	protected $head;
	
	protected $body;
	
	/**
	 * Conté el tipus de document
	 *
	 * @var unknown_type
	 */
	protected $docType;

	
	public function __construct($docType = 'none')
	{
		$this->set_docType($docType);
	}
	
	/**
	 * Assignem un valor a $docType
	 *
	 * @param string $docType
	 */
	public function set_docType($docType)
	{
		switch ($docType) {
			case 'Strict':
				$this->docType = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
				break;

			case 'Transitional':
				$this->docType = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
				break;

			case 'Frameset':
				$this->docType = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
				break;
				
			case 'None':
				$this->docType = null;
				
			default:
				$this->docType = $docType;
				break;
		}
			
	}
	
	
	/**
	 * Assignem un valor a $head
	 *
	 * @param string $head
	 */
	public function set_head($head)
	{
			$this->head = $head;
	}
	
	/**
	 * Assignem un valor a $body
	 *
	 * @param string $body
	 */
	public function set_body($body)
	{
			$this->body = $body;
	}
	
	
	public function __toString()
	{
		$return = "";
						
		/**
		 * Si hi ha DOCTYLE, el coloquem
		 */
		if ($this->docType != '')
		{
			$return = $this->docType . "\n";
		}

		
		$return .= "<HTML>\n";

		if (is_object($this->head))
		{
			$return .= (string) $this->head;
		}
		if (is_object($this->body))
		{
			$return .= (string) $this->body;
		}
		
		$return .= "</HTML>";
		
		return $return;
	}
}


/**
 * Definim l'element IFRAME HTML
 *
 *  
 * @author Àlex Corretgé <alex@corretge.cat>
 * @version 1.0
 * @package crtml
 */
class crtmlIFRAME extends crtmlBODYelement 
{
	protected $src;
	
	protected $Border;
	
	/**
	 * Constructor de la classe peIFRAME.
	 *
	 * @param string $id
	 */
	function __construct($id) 
	{
		
		$this->setId($id);
		$this->setName($id);
	}
	

	public function setBorder($border)
	{
		$this->Border = $border;
	}
	/**
	 * Assignem un valor a $src
	 *
	 * @param string $src
	 */
	public function set_src($src)
	{
			$this->src = $src;
	}
		
	/**
	 * Renderitzem el text
	 *
	 * @return string
	 */
	public function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<IFRAME ";
		
		$return .= parent::__toString();
		

		/**
		 * Si han indicat Border
		 */
		if (isset($this->Border))
		{
			$return .= " FrameBorder=\"$this->Border\"";
		}
				
		$return .= ">";
		
		
		$return .= "</IFRAME>\n";
		
		return $return;
	}

}

class crtmlTABLE extends crtmlBODYcontainer
{
	/**
	 * Indiquem que només li podem afegir <TR>
	 * @var Array
	 */
	protected $restrictConts = Array('crtmlTHEAD' => true, 
										'crtmlTFOOT' => true, 
										'crtmlTBODY' => true,
										'crtmlCAPTION' => true,
										'crtmlCOLGROUP' => true,
										'crtmlTR' => true) ;	

	
	protected $Summary; 	//      %Text;         #IMPLIED  -- purpose/structure for speech output--
	protected $Width;		//      %Length;       #IMPLIED  -- table width --
	protected $Border;		//      %Pixels;       #IMPLIED  -- controls frame width around table --
	protected $Frame;		//      %TFrame;       #IMPLIED  -- which parts of frame to render --
	protected $Rules;		//      %TRules;       #IMPLIED  -- rulings between rows and cols --
	protected $Cellspacing;	//	    %Length;       #IMPLIED  -- spacing between cells --
	protected $Cellpadding;	//	    %Length;       #IMPLIED  -- spacing within cells --
	
	
	/**
	 * Establim el valor a Summary
	 */
	public function setSummary($Summary)
	{
		$this->Summary = $Summary;
	}
		
	/**
	 * Establim el valor a Width
	 */
	public function setWidth($Width)
	{
		$this->Width = $Width;
	}

	/**
	 * Establim el valor a Border
	 */
	public function setBorder($Border)
	{
		$this->Border = $Border;
	}

	/**
	 * Establim el valor a Frame
	 */
	public function setFrame($Frame)
	{
		$this->Frame = $Frame;
	}

	/**
	 * Establim el valor a Rules
	 */
	public function setRules($Rules)
	{
		$this->Rules = $Rules;
	}

	/**
	 * Establim el valor a Cellspacing
	 */
	public function setCellspacing($Cellspacing)
	{
		$this->Cellspacing = $Cellspacing;
	}

	/**
	 * Establim el valor a Cellpadding
	 */
	public function setCellpadding($Cellpadding)
	{
		$this->Cellpadding = $Cellpadding;
	}

	public function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<TABLE ";
		
		$return .= parent::__toString();
		

		/**
		 * Si han indicat Summary
		 */
		if (isset($this->Summary))
		{
			$return .= " Summary=\"$this->Summary\"";
		}
		/**
		 * Si han indicat Width
		 */
		if (isset($this->Width))
		{
			$return .= " Width=\"$this->Width\"";
		}
		/**
		 * Si han indicat Border
		 */
		if (isset($this->Border))
		{
			$return .= " Border=\"$this->Border\"";
		}
		/**
		 * Si han indicat Frame
		 */
		if (isset($this->Frame))
		{
			$return .= " Frame=\"$this->Frame\"";
		}
		/**
		 * Si han indicat Rules
		 */
		if (isset($this->Rules))
		{
			$return .= " Rules=\"$this->Rules\"";
		}
		/**
		 * Si han indicat Cellspacing
		 */
		if (isset($this->Cellspacing))
		{
			$return .= " Cellspacing=\"$this->Cellspacing\"";
		}
		/**
		 * Si han indicat Cellpadding
		 */
		if (isset($this->Cellpadding))
		{
			$return .= " Cellpadding=\"$this->Cellpadding\"";
		}
		

				
		$return .= ">";
		
		/**
		 * Afegim els continguts
		 */
		$return .= $this->insereixContinguts();
		
		$return .= "</TABLE>\n";
		
		return $return;
	}
	
}


class crtmlTHEAD extends crtmlBODYcontainer
{
	/**
	 * Indiquem que només li podem afegir <TR>
	 * @var Array
	 */
	protected $restrictConts = Array('crtmlTR' => true) ;	
	
	/**
	 * definim el TAG com una propietat per a poder-ho fer servir 
	 * a la resta de TAG's similars
	 *
	 * @var string
	 */
	protected $TAG = "THEAD";
	
	protected $Align;	//    (left|center|right|justify|char) #IMPLIED
	protected $Char;	//    %Character;    #IMPLIED  -- alignment char, e.g. char=':' --
	protected $Charoff;	//    %Length;       #IMPLIED  -- offset for alignment char --"
	protected $Valign;	//    (top|middle|bottom|baseline) #IMPLIED"
	
	/**
	 * Establim el valor a Valign
	 */
	public function setValign($Valign)
	{
		
		switch (strtolower($Align))
		{
			case 'top':
			case 'middle':
			case 'bottom':
			case 'baseline':
				$this->Valign = $Valign;
				break;
			/**
			 * Si no és cap dels tipus permesos, donem un error fatal.
			 */
			default:
				$orig = get_class($this);
				throw new crtmlException("$orig VAlign type <b>$Valign</b> no valid.");
				break;
		}		
		
	}
		

	/**
	 * Establim el valor a Align
	 */
	public function setAlign($Align)
	{
		switch (strtolower($Align))
		{
			case 'left':
			case 'center':
			case 'right':
			case 'justify':
			case 'char':
				$this->Align = $Align;
				break;
			/**
			 * Si no és cap dels tipus permesos, donem un error fatal.
			 */
			default:
				$orig = get_class($this);
				throw new crtmlException("$orig Align type <b>$Align</b> no valid.");
				break;
		}		

	}
	/**
	 * Establim el valor a Char
	 */
	public function setChar($Char)
	{
		$this->Char = $Char;
	}
	/**
	 * Establim el valor a Charoff
	 */
	public function setCharoff($Charoff)
	{
		$this->Charoff = $Charoff;
	}

	
	public function __toString()
	{
		/**
		 * Iniciem l'objecte HTML amb els paràmetres obligatoris segons W3C si fos el cas.
		 */
		$return = "<{$this->TAG} ";
		
		$return .= parent::__toString();
	
		/**
		 * Si han indicat Align
		 */
		if (isset($this->Align))
		{
			$return .= " Align=\"$this->Align\"";
		}
		/**
		 * Si han indicat Char
		 */
		if (isset($this->Char))
		{
			$return .= " Char=\"$this->Char\"";
		}
		/**
		 * Si han indicat Charoff
		 */
		if (isset($this->Charoff))
		{
			$return .= " Charoff=\"$this->Charoff\"";
		}
		/**
		 * Si han indicat Valign
		 */
		if (isset($this->Valign))
		{
			$return .= " Valign=\"$this->Valign\"";
		}
		
				
		$return .= ">";
		
		/**
		 * Afegim els continguts
		 */
		$return .= $this->insereixContinguts();
		
		$return .= "</{$this->TAG}>\n";
		
		return $return;
	}
		
}

class crtmlTFOOT extends crtmlTHEAD
{
	/**
	 * definim el TAG com una propietat per a poder-ho fer servir 
	 * a la resta de TAG's similars
	 *
	 * @var string
	 */
	protected $TAG = "TFOOT";		
}

class crtmlTBODY extends crtmlTHEAD 
{
	/**
	 * definim el TAG com una propietat per a poder-ho fer servir 
	 * a la resta de TAG's similars
	 *
	 * @var string
	 */
	protected $TAG = "TBODY";	
}

class crtmlTR extends crtmlTHEAD 
{
	/**
	 * definim el TAG com una propietat per a poder-ho fer servir 
	 * a la resta de TAG's similars
	 *
	 * @var string
	 */
	protected $TAG = "TR";

	/**
	 * Indiquem que només li podem afegir <TH> i <TD>
	 * @var Array
	 */
	protected $restrictConts = Array('crtmlTH' => true,
										'crtmlTD' => true);
	
}

class crtmlTH extends crtmlTHEAD 
{
	/**
	 * definim el TAG com una propietat per a poder-ho fer servir 
	 * a la resta de TAG's similars
	 *
	 * @var string
	 */
	protected $TAG = "TH";

	/**
	 * Indiquem que podem afegir de tot
	 * @var Array
	 */
	protected $restrictConts = false;
	
}

class crtmlTD extends crtmlTHEAD 
{
	/**
	 * definim el TAG com una propietat per a poder-ho fer servir 
	 * a la resta de TAG's similars
	 *
	 * @var string
	 */
	protected $TAG = "TD";

	/**
	 * Indiquem que podem afegir de tot
	 * @var Array
	 */
	protected $restrictConts = false;
	
}
?>