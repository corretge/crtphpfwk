<?php
/**
 * Classe per a la creació d'arxius Excel 2003/2007
 * 
 * Especificacions: 
 * http://msdn.microsoft.com/en-us/library/aa140066.aspx
 *
 */
Class xmlxls
{
    private $fp = null;
    private $state = "CLOSED";
    private $newRow = false;
    
    protected $workSheets = Array();
    
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
    
    public function addWorkSheet($wks)
    {
    	$this->workSheets[] = $wks;
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

    	/**
    	 * processem els worksheets
    	 */
    	foreach ($this->workSheets as $wks) {
    		$this->fwrite .= $wks->Render();
    	}
    	
    	
    	/**
    	 * tanquem l'excel
    	 */
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
		//header('Content-type: application/vnd.ms-excel');
		
		header("Content-Disposition: attachment; filename=\"{$file}\"");
		header('Content-type: application/vnd.ms-excel; charset=utf-8');
		header("Content-Type: application/force-download");
		header('Content-Transfer-Encoding: binary');
    	//header("Content-Type: application/download");
    	/**
    	 * Si és un navegador explorer, cal afegir aquestes capçaleres
    	 */
		header("Pragma: ");
		header("Cache-Control: no-store");
		header("Cache-Control: private");
		//header("Expires: 0");
		/**/
		echo $this->fwrite;
		
    }

    /**
     * Retornem el que hi ha a la propietat fwrite
     */
    public function getFWrite()
    {
    	return $this->fwrite;
    }
    
    function GetHeader()
    {
    	/**
    	 * no posem   <LocationOfComponents HRef="file:///D:\AutoPlay\Docs\OFFICE11\OFFICE\"/>
    	 */
    	
        $lastsav = date("Y-m-d") . 'T' . date("H:i:s") . 'Z';

        $header = <<<EOH
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <LastAuthor>*</LastAuthor>
  <Created>$lastsav</Created>
  <LastSaved>$lastsav</LastSaved>
  <Version>11.6360</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <DownloadComponents/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <ActiveSheet>0</ActiveSheet>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:Size="12"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="sCap">
   <Font ss:Size="13.5" ss:Bold="1"/>
  </Style>
  <Style ss:ID="sDet">
   <Font ss:Size="11"/>
  </Style>
 </Styles>        
EOH;
        return $header;
    }

    function GetFooter()
    {
        return "</Workbook>";
    }
}

class xmlxlsSheet
{
	protected $name;
	protected $columns;
	protected $data = "";
	protected $dataType = Array();
	
	public $FullRows = "1";
	public $DefaultColumnWidth = "84";
	public $DefaultRowHeight = "15";
	
	/**
	 * 
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Imprimim una línia de detall
	 * Si criden aquesta funció abans de la capçalera, petarà 
	 * ja està bé.
	 */
    public function writeLine(array $line_arr)
    {
    	$this->data .= "<Row ss:AutoFitHeight=\"1\">\n";
    	
        foreach($line_arr as $i => $col)
        {
            $this->data .=   '<Cell ss:StyleID="sDet"><Data ss:Type="' . $this->dataType[$i] .'">'. $col . "</Data></Cell>\n";
        }
        
        $this->data .= "</Row>\n";
    }

    /**
     * Imprimim la capçalera i muntem algunes dades generals.
     */
    public function writeHeadLine(array $line_arr, $dataLen = null, $dataType = null)
    {

    	$tmpType = Array();
    	
    	/**
    	 * si ens envien els tipus, els coloquem
    	 */
    	If (is_array($dataType))
    	{
    		$this->dataType = $dataType;
    	}
    	else
    	{
    		$dataType = null;
    	}

    	
    	$this->data .= '<Row ss:AutoFitHeight="1">' . "\n";
    	
        foreach($line_arr as $i => $col)
        {
            $this->data .=   '<Cell ss:StyleID="sCap"><Data ss:Type="String">' . $col . '</Data></Cell>' . "\n";
            
            /**
             * Preparem uns tipus per omissió sempre string
             */
            $tmpType[] = 'String';
            
            /**
             * Carreguem les columnes
             * l'AutoFitWidth només funciona per a numèrics i data, no pas per
             * a text, així que se li pot passar la llargària.
             * En cas de que sigui data o num i la llargària passada sigui inferior
             * a la que ocupa, llavors l'autofit s'activa.
             */
            $this->columns .= '<Column ss:AutoFitWidth="1" ';
            if (is_array($dataLen) and isset($dataLen[$i]) )
            {
            	$this->columns .= " ss:Width=\"{$dataLen[$i]}\" ";
            }
            $this->columns .= ' />' . "\n";
        }
        
        $this->data .= "</Row>\n";
        
        /**
         * mirem que els tipus siguin coherents
         */
        if (is_array($dataType) and count($dataType) == count($tmpType))
        {
        	$this->dataType = $dataType;
        }
        else
        {
        	$this->dataType = $tmpType;
        }
        
     }
     
     public function Render()
     {
     	$xml = "<Worksheet ss:Name=\"{$this->name}\">\n";

     	/**
     	 * Creem la taula
     	 */
     	$xml .= "<Table x:FullColumns=\"1\"
				   x:FullRows=\"$this->FullRows\" 
				   ss:DefaultColumnWidth=\"$this->DefaultColumnWidth\" 
				   ss:DefaultRowHeight=\"$this->DefaultRowHeight\">\n";
     	
     	
     	/**
     	 * afegim les columnes preparades per autoWidth
     	 */				   
		$xml .= $this->columns;
		
		/**
		 * afegim les dades pròpiament dites del full
		 */
		$xml .= $this->data;
				   
		/**
		 * tanquem la taula i la Worksheet
		 */
		$xml .= "</Table>\n";			   
     	
		$xml .= "</Worksheet>";
		
     	return $xml;
     }
	
}

?>