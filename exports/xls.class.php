<?php
/**
 * Classe per a la creació d'arxius Excel 2003/2007
 * 
 * importada del projecte OpenSource Collabtive http://collabtive.o-dyn.de
 *
 * @example 
    $excel = new xls();
    $line = array('Data', 'Observacions');
    $excel->writeHeadLine($line, "128");

    $myArr = array('16/8/2008', 'Especial');
    $excel->writeLine($myArr);
    $excel->writeRow();
    $excel->writeColspan("<b>Total:</b>", 5);
    $excel->writeCol("<b>$totaltime</b>");

	$excel->popUp('dades.xls');
 */
Class xls
{
    private $fp = null;
    private $state = "CLOSED";
    private $newRow = false;
    
    /**
     * emulem el contingut de l'arxiu, però d'aquesta manera
     * ens permet crear Excels sense desar rés a disc.
     * @var longstring
     */
    protected $fwrite;

    /**
     * Constructora de la classe que crea Excels
     */
    function __construct()
    {
    	$this->fwrite =  $this->GetHeader() . "\n";
    }
    
    /**
     * Establim que el resultat es desarà en un arxiu
     *
     * @param string $file
     * @return boolean
     */
    function setFile($file)
    {
        return $this->open($file);
    }
    
    function open($file)
    {
        if ($this->state != "CLOSED")
        {
            return false;
        }

        if (!empty($file))
        {
            $this->fp = @fopen($file, "w+");
        }
        else
        {
            return false;
        }
        if ($this->fp == false)
        {
            return false;
        }
        $this->state = "OPENED";
        return $this->fp;
    }

    public function close()
    {
        if ($this->newRow)
        {
            $this->fwrite .= "</tr>" . "\n";
            $this->newRow = false;
        }

        $this->fwrite .=  $this->GetFooter();
        
        
        /**
         * En el cas de que s'hagi un arxiu, 
         * desem tot a l'arxiu. 
         */
        if ($this->state != "OPENED")
        {
            return false;
        }
        else
        {
        	fwrite($this->fp, $this->fwrite);
        	fclose($this->fp);
        }
        
        $this->state = "CLOSED";
        return ;
    }
    
    /**
     * Mostrem l'Excel com un arxiu que s'obre automàticament.
     */
    public function popUp($file)
    {
    	$this->close();
    	
    	/**
    	 * Imprimim la capçalera que fa que obri l'excel
    	 */
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename={$file}");
		header("Pragma: no-cache");
		header("Expires: 0");
		/**/
		echo $this->fwrite;
		
    }

    function GetHeader()
    {
        $lastsav = date("e");
        $header = <<<EOH
				<html xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:x="urn:schemas-microsoft-com:office:excel"
				xmlns="http://www.w3.org/TR/REC-html40">

				<head>
				<meta http-equiv=Content-Type content="text/html; charset=utf-8">
				<meta name=ProgId content=Excel.Sheet>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:LastAuthor>Collabtive</o:LastAuthor>
				  <o:LastSaved>$lastsav </o:LastSaved>
				  <o:Version>1</o:Version>
				 </o:DocumentProperties>
				 <o:OfficeDocumentSettings>
				  <o:DownloadComponents/>
				 </o:OfficeDocumentSettings>
				</xml><![endif]-->
				<style>
				<!--table
					{mso-displayed-decimal-separator:"\.";
					mso-displayed-thousand-separator:"\,";}
				@page
					{margin:1.0in .75in 1.0in .75in;
					mso-header-margin:.5in;
					mso-footer-margin:.5in;}
				tr
					{mso-height-source:auto;}
				col
					{mso-width-source:auto;}
				br
					{mso-data-placement:same-cell;}
				.style0
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:11.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
					.style1
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:12.0pt;
					font-weight:600;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
				td
					{mso-style-parent:style0;
					padding-top:1px;
					padding-right:1px;
					padding-left:1px;
					mso-ignore:padding;
					color:windowtext;
					font-size:11.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					border:none;
					mso-background-source:auto;
					mso-pattern:auto;
					mso-protection:locked visible;
					white-space:nowrap;
					mso-rotate:0;}
				.xl24
					{mso-style-parent:style0;
					white-space:normal;}
						.xl25
					{mso-style-parent:style1;
					white-space:normal;}
				-->
				</style>
				<!--[if gte mso 9]><xml>
				 <x:ExcelWorkbook>
				  <x:ExcelWorksheets>
				   <x:ExcelWorksheet>
					<x:Name>srirmam</x:Name>
					<x:WorksheetOptions>
					 <x:Selected/>
					 <x:ProtectContents>False</x:ProtectContents>
					 <x:ProtectObjects>False</x:ProtectObjects>
					 <x:ProtectScenarios>False</x:ProtectScenarios>
					</x:WorksheetOptions>
				   </x:ExcelWorksheet>
				  </x:ExcelWorksheets>
				  <x:WindowHeight>10005</x:WindowHeight>
				  <x:WindowWidth>10005</x:WindowWidth>
				  <x:WindowTopX>120</x:WindowTopX>
				  <x:WindowTopY>135</x:WindowTopY>
				  <x:ProtectStructure>False</x:ProtectStructure>
				  <x:ProtectWindows>False</x:ProtectWindows>
				 </x:ExcelWorkbook>
				</xml><![endif]-->
				</head>

				<body link=blue vlink=purple>
				<table x:str border=0 cellpadding=0 cellspacing=0 style="border-collapse: collapse;table-layout:fixed;">
EOH;
        return $header;
    }

    function GetFooter()
    {
        return "</table></body></html>";
    }

    function writeLine(array $line_arr, $width = 64)
    {

        $this->fwrite .=  "<tr>" . "\n";
        foreach($line_arr as $col)
        {
            $this->fwrite .=   "<td class=xl24 width=$width >$col</td>" . "\n";
        }
        $this->fwrite .=   "</tr>" . "\n";
    }

    function writeBoldLine(array $line_arr, $width = 64)
    {

        $this->fwrite .=   "<tr>" . "\n";
        foreach($line_arr as $col)
        {
            $this->fwrite .=  "<td class = x125 width=$width ><b>$col</b></td>" . "\n";
        }
        $this->fwrite .=   "</tr>" . "\n";
    }

    function writeHeadLine(array $line_arr, $width = 64)
    {

        $this->fwrite .=  "<tr>" . "\n";
        foreach($line_arr as $col)
        {
            $this->fwrite .=   "<td class = x125 width=$width ><h3>$col</h3></td>" . "\n";
        }
        $this->fwrite .=   "</tr>" . "\n";
    }

    function writeRow()
    {
        if ($this->newRow == false)
        {
            $this->fwrite .=  "<tr>" . "\n";
        }
        else
        {
            $this->fwrite .=   "</tr><tr>" . "\n";
            $this->newRow = true;
        }
    }

    function writeColspan($value, $colspan = 0)
    {
        $this->fwrite .=   "<td colspan=$colspan class=xl24 width=64 >$value</td>" . "\n";
    }

    function writeCol($value)
    {
        $this->fwrite .=   "<td class=xl24 width=64 >$value</td>" . "\n";
    }
}

?>