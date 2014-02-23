
<?php 
			
/** Error reporting */
error_reporting(E_ALL);

require_once './classes/PHPExcel.php';
require_once './classes/PHPExcel/Writer/Excel5.php';


class csv2xlsWriter {
	
	public $fileContents = array();

	public $xlsData = false;

	static public $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');


	public function __construct($fileContents) {
		$this->fileContents = $fileContents;
	}
	
	public function prepare() {
		
		// Create new PHPExcel object
		
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("csv2xls convertor");
		$objPHPExcel->getProperties()->setTitle("time date shipping");
		$objPHPExcel->getProperties()->setSubject("time date shipping");
		$objPHPExcel->getProperties()->setDescription("time date shipping");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
/*
		$objPHPExcel->getActiveSheet()
			->fromArray(
				$this->fileContents,  // The data to set
				NULL,        // Array values with this value will not be set
				'A1'         // Top left coordinate of the worksheet range where
							 // we want to set these values (default is A1)
		);
*/
		foreach ($this->fileContents as $rowNumber => $rowData) {
			foreach ($rowData as $columnNumber => $value) {
				$address = self::$alphabet[$columnNumber] . ($rowNumber+1);
				$objPHPExcel->getActiveSheet()->getCell($address)->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		/* Rename sheet
		echo date('H:i:s') . " Rename sheet\n";
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		*/

		$this->xlsData = $objPHPExcel;
		
	}
	
	public function writeAsExcel5($filename) {
		$this->prepare();
		// Save Excel 5 file
		$objWriter = new PHPExcel_Writer_Excel5($this->xlsData);
		$objWriter->save($filename);
		
		return true;
		
	}
	
	
	public function writeAsExcel2007($filename) {
		$this->prepare();
		// Save Excel 5 file
		$objWriter = new PHPExcel_Writer_Excel2007($this->xlsData);
		$objWriter->save($filename);
		
		return true;
		
	}
}