<?php

namespace Corretge\Crtmvc;

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
  protected $arrayToTableClass = 'controllerArrayToTableClass';
  protected $arrayToTableTrClass = 'controllerArrayToTableTrClass';
  protected $arrayToTableTdClass = 'controllerArrayToTableTdClass';
  protected $arrayToTableTdClassNum = 'controllerArrayToTableTdClassNum';
  protected $numberFormatDecimals = null;
  protected $numberFormatZeroBlank = false;
  protected $numberFormatDecSep = ',';
  protected $numberFormatMilersSep = '.';
  protected $Model;
  protected $uniqueId;
  protected $titolForm;

  public function __construct($caller = __FILE__)
  {
    $this->uniqueId = \str_replace('\\', '_', \get_called_class());
    $this->setAppPath(dirname($caller) . '/');
    require_once 'crtml/crtml.sform.php';
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
    /**
     * per omissió tirem de la vista que ens envia l'uri
     */
    return new View($_SERVER['REQUEST_URI']);
  }

  /**
   * Request handler. This method will be called if no method specific handler
   * is defined
   */
  protected function process()
  {
    throw new \Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
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
       * error o mostra la principal.
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
    else if ($view->getAction() == View::XMLDOM_ACTION)
    {
      header('Content-Type: text/xml; charset=UTF-8');
      echo $view->Val->saveXML();
    }
    else
    {
      throw new Exception('Unknown view action: ' . $view->getAction());
    }
  }

  /**
   * Esbrina si existeix un controller per a la URL sol·licitada.
   */
  static function instanciaController($caller = __FILE__, $namespace = __NAMESPACE__, $default = 'Inici')
  {
    $AppPath = dirname($caller) . '/';

    /**
     * Implementarem una heretabilitat de la vista per Path
     * si ens demanen Serveis/Optimització.html i no existeix
     * C/Serveis/Optimitzacio.php però sí que existexi
     * C/Serveis.php demanarem que s'instancii aquesta enlloc de saltar a
     * error i mostra la principal.
     */
    $u = parse_url($_SERVER['REQUEST_URI']);
    $u['path'] = str_replace('.html', '', $u['path']);
    /**
     * eliminem els paràmetres, tot el que vingui
     * a partir de ? o , fora
     */
    $jPosA = \strpos($u['path'], '?');
    $jPosB = \strpos($u['path'], ',');

    /**
     * @todo implementar també l'interrogant
     */
    if ($jPosB > 0)
      $u['path'] = substr($u['path'], 0, $jPosB );

    
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
       * si existeix el controlador el carreguem
       */
      $file = $AppPath . 'C/' . implode('/', $au) . '.php';
      if (is_file($file))
      {
        require_once $file;
        break;
      }

      /**
       * esborrem l'element que estavem processant.
       */
      unset($au[$i]);
    }

    /**
     * trobem el nom de la classe controller per omissió si queda alguna cosa, si no,
     * el controlador base.
     */
    if (empty($au))
    {
      $file = $AppPath . "C/{$default}.php";
      require_once $file;
      $class = "{$namespace}\\{$default}";
    }
    else
    {
      $class = "{$namespace}\\" . implode('_', $au);
    }


    /**
     * retornem el controlador instanciat
     */
    return new $class($caller);
  }

  /**
   * Esbrina si existeix un controller per a la URL sol·licitada.
   */
  static function instanciaModel($caller = __FILE__, $namespace = __NAMESPACE__, $default = 'Inici')
  {
    /**
     * el caller sempre serà un Controller, així que tallem
     * just a on hi ha la C
     */
    $AppPath = dirname($caller) . '/';
    $jPos = \strpos($AppPath, '/C/');
    $AppPath = \substr($AppPath, 0, $jPos + 1);

    /**
     * Implementarem una heretabilitat de la vista per Path
     * si ens demanen Serveis/Optimització.html i no existeix
     * C/Serveis/Optimitzacio.php però sí que existexi
     * C/Serveis.php demanarem que s'instancii aquesta enlloc de saltar a
     * error i mostra la principal.
     */
    $u = parse_url($_SERVER['REQUEST_URI']);
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
       * si existeix el controlador el carreguem
       */
      $file = $AppPath . 'M/' . implode('/', $au) . '.php';

      if (is_file($file))
      {
        require_once $file;
        break;
      }

      /**
       * esborrem l'element que estavem processant.
       */
      unset($au[$i]);
    }

    /**
     * trobem el nom de la classe controller per omissió si queda alguna cosa, si no,
     * el controlador base.
     */
    if (empty($au))
    {
      $file = $AppPath . "M/{$default}.php";
      require_once $file;
      $class = "{$namespace}\\{$default}";
    }
    else
    {
      $class = "{$namespace}\\" . implode('_', $au);
    }

    /**
     * retornem el controlador instanciat
     */
    return new $class($caller);
  }

  protected function arrayToForm($array)
  {
    $ret = new \sFormTable($this->titolForm, $this->uniqueId);

    foreach ($array as $key => $val)
    {
      $ret->addInput($key, $key, 'text', $val);
    }

    $ret->setAction('');
    $ret->setMethod('POST');

    $desa = new \crtmlBUTTON('Desa');
    $esborra = new \crtmlBUTTON('Esborra');
    $ret->addButton($desa);
    $ret->addButton($esborra);
    return $ret;
  }

  protected function arrayToTable($array)
  {
    $ret = new \crtmlTABLE();

    $ret->setClass($this->arrayToTableClass);

    foreach ($array as $key => $val)
    {
      $tr = new \crtmlTR(null, $this->arrayToTableTrClass);

      $td = new \crtmlTD($key, $this->arrayToTableTdClass);
      $tr->addContingut($td);
      if (\is_numeric($val))
      {
        $td = new \crtmlTD($this->number_format($val), $this->arrayToTableTdClassNum);
      }
      else
      {
        $td = new \crtmlTD($val, $this->arrayToTableTdClass);
      }

      $tr->addContingut($td);

      $ret->addContingut($tr);
    }


    return $ret;
  }

  public function number_format($number, $dec=null, $decSep = null, $milSep = null)
  {
    /**
     * si és zero i ens han dit que zero és blanc, sortim
     */
    if ($this->numberFormatZeroBlank and $num == 0)
    {
      return '';
    }

    if (empty($dec))
      $dec = $this->numberFormatDecimals;

    if (empty($decSep))
      $decSep = $this->numberFormatDecSep;

    if (empty($milSep))
      $milSep = $this->numberFormatMilersSep;

    /**
     * si és numèric, apliquem el número de decimals
     */
    if (is_numeric($dec))
    {
      return \number_format($number, $dec, $decSep, $milSep);
    }
    /**
     * Si no és numèric (segurament null) ho formatem nosaltres
     */
    else
    {

      $aNum = explode('.', $number);

      /**
       * si és negatiu, ho respectem
       */
      if (\substr($aNum[0], 0, 1) == '-')
      {
        $strNum = '- ';
        $aNum[0] = \substr($aNum[0], 1);
      }
      else
      {
        $strNum = '';
      }

      $strNum .= \number_format($aNum[0], null, null, $milSep);

      /**
       * si hi ha decimals, els afegim
       */
      if (isset($aNum[1]))
      {
        $strNum .= $decSep . $aNum[1];
      }

      return $strNum;
    }
  }

}

class DefaultController extends Controller
{

  protected function get()
  {

  }

}