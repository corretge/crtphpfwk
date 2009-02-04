<?php
/**
 * test per a provar l'enllaç d'objectes 
 */

require_once('Zend/Date.php');

/**
 * fem un dump del valor de la data
 *
 * @param unknown_type $Zend_Date
 */
function data_dump($Zend_Date)
{
	echo "\n";
	if (is_array($Zend_Date))
	{
		foreach ($Zend_Date as $data)
		{
			echo "{$data->get(Zend_Date::ISO_8601)}\n";
		}
	}
	else
	{
		echo "{$Zend_Date->get(Zend_Date::ISO_8601)}\n";
	}
}

echo "<pre>";

echo "<h1>Assignació de Zend_Date's normal</h1>";
$data1 = new Zend_Date();
$data2 = new Zend_Date();
$aDate = array();


echo "data1 abans";
data_dump($data1);

$aDate[] =  $data1;
$data1->add('11:22:33', Zend_Date::TIMES);
$aDate[] =   $data1;
$data1->add('11:22:33', Zend_Date::TIMES);
$aDate[] =   $data1;

echo "data1 final";
data_dump($data1);

echo "aDate ";
data_dump($aDate);



/**
 * Si no posem clone el que es fa és assignar un enllaç
 * de l'objecte, i quan es recupera l'array, totes les
 * possicions tenen el mateix valor, el valor darrer 
 * de $data1 provocant molts maldecaps.
 * 
 * @link http://es.php.net/clone
 *  
 */
echo "<h1>Assignació de Zend_Date's amb clone</h1>";
$aDate = array();
echo "data2 abans";
data_dump($data2);

$aDate[] =  clone $data2;
$data2->add('11:22:33', Zend_Date::TIMES);
$aDate[] =  clone $data2;
$data2->add('11:22:33', Zend_Date::TIMES);
$aDate[] =  clone $data2;

echo "data2 final";
data_dump($data2);

echo "aDate ";
data_dump($aDate);



?>