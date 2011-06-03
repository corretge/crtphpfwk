<?php

namespace Corretge\Crtmvc;

/**
 * Base View for all pages
 *
 * @link http://blog.devartis.com/?p=117
 * @author german
 *
 * Modifications by:
 * @author alex@corretge.cat
 * @version 1.1
 */
class View {
	const RENDER_ACTION = 'render';
	const REDIRECT_ACTION = 'redirect';
  const XMLDOM_ACTION = 'xmldom';

	protected $Url;
	public $Val;
	protected $Action;
  

	public function __construct($url, $val=array(), $action=View::RENDER_ACTION) {
		
		$this->setUrl($url);
		$this->setVal($val);
		$this->setAction($action);
		
	}

	protected function setAction($Action) {
		$this->Action = $Action;
	}

	public function getAction() {
		return $this->Action;
	}

	protected function setVal($Val) {
		$this->Val = $Val;
	}

	public function getVal() {
		return $this->Val;
	}

	protected function setUrl($Url) {

		$this->Url = $Url;
		
	}

	public function getUrl() {
		return $this->Url;
	}

  

}