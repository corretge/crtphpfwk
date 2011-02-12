<?php

/**
 * Base Controller for all pages
 * Handles session management, GET/POST requests and response rendering
 *
 * @link http://blog.devartis.com/?p=117
 * @author german
 *
 * Modifications by:
 * @author alex@corretge.cat
 * @version 1.1
 */
class Controller
{

	protected $AppPath;

	public function __construct($caller = __FILE__)
	{
		$this->setAppPath(dirname($caller) . '/');
	}

	protected function setAppPath($AppPath = __FILE__)
	{
		$this->AppPath = $AppPath;
	}

	public function getAppPath()
	{
		return $this->AppPath;
	}

	/**
	 * This method should be called from the controller PHP to handle
	 * the current request
	 */
	public function start()
	{
		session_start();
		$this->init();

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$view = $this->post();
		}
		else
		{
			$view = $this->get();
		}

		if ($view != null)
		{
			$this->display($view);
		}
	}

	/**
	 * Override this method to initalize the controller before handling
	 * the request
	 */
	protected function init()
	{
		
	}

	/**
	 * GET request handler
	 */
	protected function get()
	{

		/**
		 * per omissió tirem de la vista que ens envia l'uri
		 */
		return new View($_SERVER['REQUEST_URI']);
	}

	/**
	 * GET request handler
	 */
	protected function post()
	{
		$this->process();
	}

	/**
	 * Request handler. This method will be called if no method specific handler
	 * is defined
	 */
	protected function process()
	{
		throw new Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
	}

	/**
	 * Populates the given object with POST data.
	 * If not object is given a StdClass is created.
	 * @param StdClass $obj
	 * @return StdClass
	 */
	protected function populateWithPost($obj = null)
	{
		if (!is_object($obj))
		{
			$obj = new StdClass();
		}

		foreach ($_POST as $var => $value)
		{
			$obj->$var = trim($value); //here you can add a filter, like htmlentities ...
		}

		return $obj;
	}

	private function display($view)
	{
		if ($view->getAction() == View::RENDER_ACTION)
		{

			/**
			 * Implementarem una heretabilitat de la vista per Path
			 * si ens demanen Serveis/Optimització.html i no existeix
			 * V/Serveis/Optimitzacio.phml però sí que existexi
			 * v/Serveis.phtml mostrem aquesta enlloc de saltar a
			 * error i mostra la principal.
			 */
			$u = parse_url($view->getUrl());
			$u['path'] = str_replace('.html', '', $u['path']);
			$au = explode('/', $u['path']);
			/**
			 * ens carreguem el primer element, que és sempre buit.
			 */
			unset($au[0]);

			$n = count($au);

			$ur = "";
			/**
			 * per seguretat ho limitem a 8 nivells
			 */
			if ($n > 8)
			{
				$n = 8;
			}

			/**
			 * processem el path que ens ha arribat
			 */
			for ($i = $n; $i > 0; $i--)
			{
				/**
				 * si existeix la vista la guardem per mostrar-la
				 */
				$file = $this->getAppPath() . 'V/' . implode('/', $au) . '.phtml';
				if (is_file($file))
				{
					break;
				}

				/**
				 * esborrem l'element que estavem processant.
				 */
				unset($au[$i]);
			}



			if (is_file($file))
			{
				include($file);
			}
			else
			{
				/**
				 * si no existeix, sempre hi haurà un V/Inici.phtml
				 */
				include($this->getAppPath() . 'V/Inici.phtml');
			}
		}
		else if ($view->getAction() == View::REDIRECT_ACTION)
		{
			header('Location: ' . $view->url);
		}
		else
		{
			throw new Exception('Unknown view action: ' . $view->getAction());
		}
	}

}

class DefaultController extends Controller
{

	protected function get()
	{
		
	}

}