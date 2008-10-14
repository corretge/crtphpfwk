<?php

require_once 'xmlxls.class.php';

$book = new xmlxls();

$sheetA = new xmlxlsSheet('Dades');
$sheetB = new xmlxlsSheet('Paràmetres');

$sheetA->writeHeadLine(array('Data', 'Tipus', 'Observacions'));
$sheetA->writeLine(array('01/8/2008', 'C', 'test A'));
$sheetA->writeLine(array('20/10/2008', 'B', 'test A2'));


$sheetB->writeHeadLine(array('Paràmetre', 'Valor'));
$sheetB->writeLine(array('parmA', 'Loren Ipsum Aqua'));
$sheetB->writeLine(array('parmB', 'Vini, vidi, vinci'));


$book->addWorkSheet($sheetA);
$book->addWorkSheet($sheetB);

$book->popUp('test.xls');

?>