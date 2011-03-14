<?php
/**
 * Fem que sempre els errors provoquin una excepció catchejable
 * 
 * @author alex@corretge.cat
 * @link http://corretge.cat
 */


namespace Corretge\Crtmvc;

function ErrorHandler($errno, $errstr, $errfile, $errline)
{
	//trigger_error("SYS0033/{$errno} - {$errstr} a {$errfile} línia {$errline}.");
	throw new \Exception("{$errno} - {$errstr} in {$errfile} at line {$errline}.");

}


set_error_handler("\Corretge\Crtmvc\ErrorHandler");
