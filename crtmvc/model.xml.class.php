<?php

/**
 * Breu descripció de l'script
 * 
 * @author alex@corretge.cat
 * @link http://corretge.cat
 */

namespace Corretge\Crtmvc;

class ModelXml
{

	public function getFields($nodes, $level)
	{
		

		$name = $nodes->getName();
		$type = $nodes->getType();

		$ret = "<fieldset><legend>{$name}</legend>";


		if ($type == 1)
		{
		 foreach ($nodes as $node)
		 {
			$ret .= $node->getName() . " ({$node->getType()}): ";

			$level ++;
			if ($level == 1)
			{
				$ret .= $this->getFields($node->children(), $level);
			}
			else
			{
				$ret .= $this->getFields($node, $level);
			}
		 }
		}

		return $ret . "</fieldset>";
	}

}

class ModelXmlElement extends \SimpleXMLElement
{

	public function getName()
	{
		return dom_import_simplexml($this)->nodeName;
	}

	public function getType()
	{
		if (count($this) > 0)
		{
			return dom_import_simplexml($this)->nodeType;
		}
			else
			{
			 return null;
			}
	}

	public function getVal()
	{

		return (string) $this;
	}

	public function getInputTag($prefix = 'input', $type="text", $label = true)
	{
		$nom = $this->getName();

		$val = $this->getVal();

		$ret = <<< hereInputTag
		<label for id="{$prefix}{$nom}">{$nom}</label>
		<input  id="{$prefix}{$nom}" type="{$type}"  name="{$prefix}{$nom}" value="{$val}"/>

hereInputTag;

		return $ret;
	}

}