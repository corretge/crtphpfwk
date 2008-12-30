#!/usr/bin/php
<?php
/**
 * descomposem un arxiu creat per MySqlDump --all-databases
 * en un arxiu per cada DataBase
 */

echo "\nsplitMySqlDump\n";

if (!isset($argv[1]))
{
	die("\nFalta arxiu d'entrada\n");
}

$fIn = fopen($argv[1], "r");
if ($fIn)
{
	while (!feof($fIn))
	{
		$line = fgets($fIn);
		/**
		 * Preguntem si hi ha un CREATE DATABASE a la línia, i 
		 * aprofitem el viatge per a recuperar el nom de la 
		 * database en qüestió.
		 * ATENCIÓ!
		 * mirar si el mysqldump genera el 32312 si no, canviar-ho.
		 */ 
		if(preg_match('%CREATE DATABASE /\*!32312 IF NOT EXISTS\*/ `(.+?)`%', $line, $matches))
		{
			if(isset($fOut))
			{
				fclose($fOut);
				unset($fOut);
			}
			
			echo "\nProcedim amb DataBase {$matches[1]}\n";
			
			$fOut = fopen($matches[1] . '.sql', 'w');
			fwrite($fOut, $line . "\n");
		}
		else
		{
			if(isset($fOut))
			{
				fwrite($fOut, $line . "\n");
			}
		}
	}

	fclose($fOut);
	fclose($fIn);
	echo "\nOk\n";
}
else
{
	echo "\nError llegint arxiu {$argv[1]}\n";
}


?>