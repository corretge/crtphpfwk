<?php
/**
 * conjunt de funcions d'ajuda a la depuració de porgrames
 */


/**
 * Recuperem un var_dump en una string per a poder-la 
 * enviar via ajax per exemple
 *
 * @param unknown_type $var
 * @param string $name
 * @return string
 */
function rtv_var_dump($var, $name = false)
{
	/**
	 * iniciem la captura del buffer de sortida
	 * i passarem el resultat de la comanda 
	 * var_dump a la variable a retornar.
	 */
	ob_start();
	var_dump($var);
	$return = ob_get_contents();
	ob_end_clean();
	
	/**
	 * si indiquen el nom de la variable la posem
	 */
	if ($name)
	{
		$return = $name . " = " . $return;
	}
	
	return $return;
}
?>